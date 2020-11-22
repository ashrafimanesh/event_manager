<?php


namespace App\Traits;

/**
 * Trait EloquentCreateOrUpdate
 * Check duplicate data on model during create new data
 * @package App\Traits
 */
trait EloquentCreateOrUpdate
{
    public static function createOrUpdate($newData, \Closure $finderBuilder, \Closure $updater = null, &$found = false)
    {
        $result = $finderBuilder(static::query())->first();
        if(!$result){
            $result = static::create($newData);
        }
        else{
            $found = true;
            if($updater){
                $updater($result);
            }
        }
        return $result;
    }
}
