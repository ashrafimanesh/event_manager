<?php

namespace App;

use App\Traits\EloquentCreateOrUpdate;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed role
 */
class OrganUserRole extends Model
{
    use EloquentCreateOrUpdate;

    protected $table = 'organ_user_roles';

    protected $fillable = [
        'organ_user_id', 'role_id', 'created_by', 'role_name'
    ];

    public function role(){
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function getRoleNameAttribute(){
        if(!$this->role){
            return '';
        }
        return $this->role->name;
    }
}
