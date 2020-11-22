<?php

namespace App;

use App\Traits\EloquentCreateOrUpdate;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use EloquentCreateOrUpdate;

    const SUPER_ADMIN = 'super_admin';
    const ADMIN = 'admin';
    const EVENT_ORGANIZER = 'event_organizer';
    const PARTICIPANT = 'participant';

    protected $table = 'roles';


    public function scopeExceptUserRoles($builder, $organUserId){
        $userRoles = OrganUserRole::query()->select('role_id')->where('organ_user_id', $organUserId)->get();
        if(!$userRoles){
            return $builder;
        }
        $ids = [];
        $userRoles->each(function($row)use(&$ids){
            $ids[] = $row->role_id;
        });
        return $builder->whereNotIn('id', $ids);
    }

}
