<?php

namespace App;

use App\Traits\EloquentCreateOrUpdate;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed roles
 * @property User user
 * @property mixed roles_count
 * @property Organ|null organ
 */
class OrganUser extends Model
{
    use EloquentCreateOrUpdate;

    protected $table = "organ_users";

    protected $fillable = [
        'user_id', 'organ_id', 'created_by'
    ];

    public function roles(){
        return $this->hasMany(OrganUserRole::class, 'organ_user_id');
    }

    public function organ(){
        return $this->belongsTo(Organ::class, 'organ_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getOrganNameAttribute(){
        return $this->organ->name;
    }

    public function getRolesCountAttribute(){
        return OrganUserRole::query()->where('organ_user_id', $this->id)->count();
    }
}
