<?php

namespace App;

use App\Traits\EloquentCreateOrUpdate;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed status
 * @property int|null active_by
 * @property Carbon|mixed active_at
 * @property int event_id
 * @property mixed turn_order
 * @property EventModel event
 */
class EventParticipant extends Model
{
    use EloquentCreateOrUpdate;

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    protected $fillable = [
        'event_id', 'organ_user_id', 'status', 'turn_order', 'created_by', 'active_by', 'active_at', 'turned_at'
    ];

    protected $dates = [
        'active_at',
        'turned_at'
    ];

    public function scopeActive($builder, $active = true){
        return $builder->where('status', $active ? self::STATUS_ACTIVE : self::STATUS_INACTIVE);
    }

    public function event(){
        return $this->belongsTo(EventModel::class, 'event_id');
    }

    public function organUserId(){
        return $this->hasMany(OrganUser::class, 'organ_user_id');
    }

    public function getStatusTitleAttribute(){
        return self::statuses()[$this->status]['title'];
    }

    public function getParticipantAttribute(){
        /** @var OrganUser $model */
        $model = OrganUser::query()->with('user')->find($this->organ_user_id);
        return $model ? $model->user : null;
    }

    public function getParticipantNameAttribute(){
        return $this->participant ? $this->participant->name : '-';
    }

    public static function statuses()
    {
        return [
            self::STATUS_ACTIVE=>['title'=>trans('global.StatusActive')],
            self::STATUS_INACTIVE=>['title'=>trans('global.StatusInactive')],
        ];
    }
}
