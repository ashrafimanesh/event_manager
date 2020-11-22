<?php

namespace App;

use App\Traits\EloquentCreateOrUpdate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Organ extends Model
{
    use EloquentCreateOrUpdate;

    const DEFAULT = 'default';

    protected $table = "organs";
    protected $fillable = [
        'name','created_by'
    ];

    public function getUsersCountAttribute(){
        return Cache::get('organ_'.$this->id.'_users_count', function(){
            $count =  OrganUser::query()->where('organ_id', $this->id)->count();
            Cache::put('organ_'.$this->id.'_users_count', $count, 10);
            return $count;
        });
    }

    public function scopeExceptUserOrgans($builder, $userId){
        $userOrgans = OrganUser::query()->select('organ_id')->where('user_id', $userId)->get();
        if(!$userOrgans){
            return $builder;
        }
        $ids = [];
        $userOrgans->each(function($row)use(&$ids){
            $ids[] = $row->organ_id;
        });
        return $builder->whereNotIn('id', $ids);
    }

}
