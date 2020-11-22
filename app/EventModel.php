<?php

namespace App;

use App\Traits\EloquentCreateOrUpdate;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed type
 * @property mixed payment_type
 * @property mixed participants_count
 * @property boolean started
 * @property boolean expired
 * @property Carbon start_date
 * @property Carbon expired_date
 * @property mixed start_by
 * @property int|null expired_by
 * @property mixed active_participants_count
 */
class EventModel extends Model
{
    use EloquentCreateOrUpdate;

    const TYPE_GOLD_DAY = 'gold_day';
    const TYPE_EURO_DAY = 'euro_day';
    const TYPE_DOLLAR_DAY = 'dollar_day';
    const TYPE_MEETING = 'meeting';
    const TYPE_ONLINE_PARTY = 'online_party';
    const PAYMENT_TYPE_MONEY = 'money';
    const PAYMENT_TYPE_QGold = 'q_gold';
    const PAYMENT_TYPE_BITCOIN = 'bitcoin';

    protected $table = 'events';

    protected $fillable = [
        'name', 'type', 'recurring_intervals', 'organ_id', 'payment_type', 'payment_amount',
        'hold_by', 'start_by', 'start_date', 'expired_date', 'expired_by',
        'created_by'
    ];

    protected $dates = [
        'start_date',
        'expired_date'
    ];

    public function getTypeTitleAttribute(){
        return self::allTypes()[$this->type]['title'];
    }

    public function getPaymentTypeTitleAttribute(){
        return self::allPaymentTypes()[$this->payment_type]['title'];
    }

    public function organ(){
        return $this->belongsTo(Organ::class, 'organ_id');
    }

    public function getStartedAttribute(){
        return $this->start_date ? true : false;
    }

    public function getExpiredAttribute(){
        return $this->expired_date ? true : false;
    }

    public function getStatusTitleAttribute(){
        switch (true){
            case $this->started && !$this->expired:
                $status = trans('global.Started');
                break;
            case $this->expired:
                $status = trans('global.Expired');
                break;
            default:
                $status = trans('global.WaitToStart');
        }
        return $status;
    }

    public function scopeExceptOrganEvents($builder, $organId){
        $organEvents = self::query()->select('id')->where('organ_id', $organId)->get();
        if(!$organEvents){
            return $builder;
        }
        $ids = [];
        $organEvents->each(function($row)use(&$ids){
            $ids[] = $row->organ_id;
        });
        return $builder->whereNotIn('id', $ids);
    }

    public function getParticipantsCountAttribute(){
        return EventParticipant::query()->where('event_id', $this->id)->count();
    }

    public function getActiveParticipantsCountAttribute(){
        return EventParticipant::query()->where(['event_id'=> $this->id, 'status'=>EventParticipant::STATUS_ACTIVE])->count();
    }

    public function isParticipant($userOrganId)
    {
        return (EventParticipant::where(['event_id'=>$this->id, 'organ_user_id'=> $userOrganId])->count()>0);
    }

    public static function allPaymentTypes()
    {
        return [
            self::PAYMENT_TYPE_MONEY=>['title'=>trans('global.Money')],
            self::PAYMENT_TYPE_QGold=>['title'=>trans('global.QGold')],
            self::PAYMENT_TYPE_BITCOIN=>['title'=>trans('global.Bitcoin')],
        ];

    }

    public static function allTypes()
    {
        return [
            self::TYPE_MEETING=>['title'=>trans('global.Meeting')],
            self::TYPE_ONLINE_PARTY=>['title'=>trans('global.OnlineParty')],
            self::TYPE_GOLD_DAY=>['title'=>trans('global.GoldDay')],
            self::TYPE_EURO_DAY=>['title'=>trans('global.EuroDay')],
            self::TYPE_DOLLAR_DAY=>['title'=>trans('global.DollarDay')],
        ];
    }

    public static function validateType($type)
    {
        return isset(self::allTypes()[$type]);
    }

    public static function validatePaymentType($type)
    {
        return isset(self::allPaymentTypes()[$type]);
    }

    public function expireAble()
    {
        return $this->started && !$this->expired;
    }

    public function startAble()
    {
        return $this->active_participants_count > 0 && !$this->started && !$this->expired;
    }
}
