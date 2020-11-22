<?php

namespace App\Http\Controllers;

use App\EventParticipant;
use App\Providers\AuthServiceProvider;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ParticipantsController extends Controller
{
    public function active($eventId, Request $request){
        $participantId = $request->input('event_participant_id');
        $turn_order = (int)$request->input('turn_order');
        /** @var EventParticipant $participant */
        $participant = EventParticipant::query()->where('event_id', $eventId)->find($participantId);

        if(!$participant || !$turn_order || $turn_order<0){
            return response()->json(['status' => false, 'message' => trans('global.InvalidInput')]);
        }
        if(!Gate::allows(AuthServiceProvider::MANAGE_PARTICIPANTS, $participant)){
            return response()->json(['status' => false, 'message' => trans('global.AccessDeniedAction'), 'code'=>-504]);
        }

        $participant->status = EventParticipant::STATUS_ACTIVE;
        $participant->turn_order = $turn_order;
        $participant->active_by = Auth::id();
        $participant->active_at = Carbon::now();
        $participant->save();
        return response()->json(['status'=>true, 'message'=>trans('global.SuccessAction'), 'data'=>['eventId'=>$participant->event_id]]);
    }
}
