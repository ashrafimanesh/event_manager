<?php

namespace App;

use App\Traits\EloquentCreateOrUpdate;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

/**
 * @property mixed id
 * @property mixed organs_count
 */
class User extends Authenticatable
{
    use Notifiable, EloquentCreateOrUpdate;

    /** @var string super admin session key name */
    const SESSION_SUPER_ADMIN_KEY = 'super_admin';
    /** @var string admin session key name */
    const SESSION_ADMIN_KEY = 'admin';
    /** @var string current organ session key name */
    const SESSION_CURRENT_ORGAN_KEY = 'current_organ';
    const SESSION_CURRENT_USER_ORGAN_KEY = 'current_user_organ';
    const SESSION_EVENT_ORGANIZER_KEY = 'event_organizer';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'created_by'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    /**
     * @var array
     */
    private $_organs = [];

    public function getRoles(){
        $organId = $this->getCurrentOrganId();
        if(!$organId){
            return collect([]);
        }
        $organ = $this->findOrgan($organId);
        if(!$organ){
            return collect([]);
        }
        return $organ->roles;

    }

    public function isSuperAdmin($session = false)
    {
        $sessionKey = $session ? self::SESSION_SUPER_ADMIN_KEY : '';
        $roleName = Role::SUPER_ADMIN;
        return $this->checkRole($sessionKey, $roleName);
    }

    public function isAdmin($session = false)
    {
        if($this->isSuperAdmin($session)){
            return true;
        }
        $sessionKey = $session ? self::SESSION_ADMIN_KEY : '';
        $roleName = Role::ADMIN;
        return $this->checkRole($sessionKey, $roleName);
    }

    public function isEventOrganizer($session = false)
    {
        if($this->isAdmin($session)){
            return true;
        }
        $sessionKey = $session ? self::SESSION_EVENT_ORGANIZER_KEY : '';
        $roleName = Role::EVENT_ORGANIZER;
        return $this->checkRole($sessionKey, $roleName);
    }

    /**
     * @param $organId
     * @return OrganUser|null
     */
    public function findOrgan($organId)
    {
        if($this->_organs[$organId] ?? false){
            return $this->_organs[$organId];
        }
        $this->_organs[$organId] = OrganUser::query()->where([
            'user_id' => $this->id,
            'organ_id' => $organId
        ])->first();
        return $this->_organs[$organId];
    }

    /**
     * @param string $sessionKey
     * @param string $roleName
     * @return bool|mixed
     */
    protected function checkRole(string $sessionKey, string $roleName)
    {
        if ($sessionKey) {
            $result = Session::get($sessionKey);
            if (!is_null($result)) {
                return $result;
            }
        }
        $roles = $this->getRoles();
        $isAdmin = false;
        if ($roles->count()) {
            $isAdmin = $roles->where('role_name', $roleName)->count() > 0;
        }
        if ($sessionKey) {
            Session::put($sessionKey, $isAdmin);
        }
        return $isAdmin;
    }

    /**
     * @return int|null
     */
    public function getCurrentOrganId()
    {
        $value = Session::get(self::SESSION_CURRENT_ORGAN_KEY);
        if(!is_null($value)){
            return $value;
        }
        $userOrgan = OrganUser::query()->where('user_id', Auth::id())->first();
        $this->setCurrentUserOrganId($userOrgan->id);
        $this->setCurrentOrganId($userOrgan->organ_id);
        return $userOrgan->organ_id;
    }

    public function setCurrentOrganId($organId)
    {
        Session::put(self::SESSION_CURRENT_ORGAN_KEY, $organId);
        Session::forget([self::SESSION_SUPER_ADMIN_KEY, self::SESSION_ADMIN_KEY, self::SESSION_EVENT_ORGANIZER_KEY]);

    }

    public function getCurrentUserOrganId(){
        $value = Session::get(self::SESSION_CURRENT_USER_ORGAN_KEY);
        if(!is_null($value)){
            return $value;
        }

        $userOrgan = OrganUser::query()->where('user_id', Auth::id())->first();
        $this->setCurrentUserOrganId($userOrgan->id);
        $this->setCurrentOrganId($userOrgan->organ_id);
        return $userOrgan->id;
    }

    public function setCurrentUserOrganId($organUserId){
        Session::put(self::SESSION_CURRENT_USER_ORGAN_KEY, $organUserId);
        Session::forget([self::SESSION_SUPER_ADMIN_KEY, self::SESSION_ADMIN_KEY, self::SESSION_EVENT_ORGANIZER_KEY]);
    }

    public function getOrgansCountAttribute(){
        return OrganUser::query()->where('user_id', $this->id)->count();
    }

    public function getOrgans(){
        return OrganUser::query()->where('user_id', $this->id)->get();
    }

    public function getOrgansNameAttribute(){
        $name = '';
        /** @var OrganUser $row */
        foreach($this->getOrgans() as $row){
            if($row->id == $this->getCurrentUserOrganId()){
                $name.= '<b>'.$row->organ->name.'</b>  ,';
            }
            else{
                $name.= $row->organ->name.'  ,';
            }
        }
        return rtrim($name, ',');
    }

    public function getRolesNameAttribute(){
        return Cache::get($this->getRolesNameCacheKey(), function(){
            $name = [];
            foreach($this->getRoles() as $role){
                $name[$role->role_id] = $role->role_name;
            }
            $name = implode(array_values($name), '  ,');
            if($name){
                Cache::put($this->getRolesNameCacheKey(), $name, 10);
            }
            return $name;
        });
    }

    public function getRolesNameCacheKey(){
        return 'user_roles_'.$this->id;
    }

    public function removeRolesNameCache()
    {
        Cache::forget($this->getRolesNameCacheKey());
    }
}
