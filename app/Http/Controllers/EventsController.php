<?php

namespace App\Http\Controllers;

use App\EventModel;
use App\EventParticipant;
use App\Organ;
use App\Providers\AuthServiceProvider;
use App\User;
use App\View\Partials\EventsListActions;
use App\View\Partials\ParticipantsListActions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\Facades\DataTables;

class EventsController extends Controller
{
    public function index($organId = null)
    {
        $data = [];
        if ($organId) {
            $data['forOrgan'] = Organ::query()->find($organId);
        }
        return view('events', $data);
    }

    public function getList()
    {
        $query = EventModel::query()->latest();
        $datatable = DataTables::of($query)->addIndexColumn();
        $datatable->addColumn('type_title', function ($row) {
            return $row->type_title;
        });
        $datatable->addColumn('status_title', function ($row) {
            return $row->status_title;
        });
        $datatable->addColumn('payment_type_title', function ($row) {
            return $row->payment_type_title;
        });
        return $datatable
            ->addColumn('action', function ($row) {
                return (new EventsListActions($row))->render();
            })->rawColumns(['action'])->make(true);
    }

    public function store(Request $request)
    {
        $name = $request->input('name');
        $type = $request->input('type');
        $organId = $request->input('organ_id');
        $recurring_intervals = (int)$request->input('recurring_intervals');
        if (!$name || !$type || !$organId || !EventModel::validateType($type)) {
            return response()->json(['status' => false, 'message' => trans('global.InvalidInput')]);
        }
        $found = false;
        $event = EventModel::createOrUpdate([
            'name' => $name,
            'type' => $type,
            'organ_id' => $organId,
            'recurring_intervals' => $recurring_intervals,
            'created_by' => Auth::id(),
        ], function ($builder) use ($name, $organId) {
            return $builder->where(['name' => $name, 'organ_id' => $organId]);
        }, null, $found);

        return response()->json([
            'status' => $event && !$found,
            'message' => $event ? ($found ? trans('global.DuplicateData') : trans('global.SuccessAction')) : trans('global.ErrorAction'),
            'data' => $event]);
    }

    public function join($eventId, Request $request)
    {
        $eventModel = EventModel::query()->where(['id' => $eventId])->first();
        if (!$eventModel) {
            return redirect(route('events'))->withErrors([trans('global.InvalidInput')]);
        }
        if (!Gate::allows(AuthServiceProvider::JOIN_TO_EVENT, $eventModel)) {
            return redirect()->route('504');
        }
        /** @var User $user */
        $user = Auth::user();
        $currentUserOrganId = $user->getCurrentUserOrganId();
        $eventParticipant = EventParticipant::createOrUpdate([
            'event_id' => $eventId,
            'organ_user_id' => $currentUserOrganId,
            'status' => EventParticipant::STATUS_INACTIVE,
            'created_by' => $user->id,
        ], function ($builder) use ($eventId, $currentUserOrganId) {
            return $builder->where([
                'event_id' => $eventId,
                'organ_user_id' => $currentUserOrganId,
            ]);
        }, null, $found);
        if ($eventParticipant && !$found) {
            return redirect(route('events.index'))->withInput(['success' => trans('global.SuccessAction')]);
        } elseif ($found) {
            return redirect(route('events.index'))->withErrors([trans('global.DuplicateData')]);
        }

        return redirect(route('events.index'))->withErrors([trans('global.ErrorAction')]);
    }

    public function participants($eventId)
    {
        $eventModel = EventModel::query()->where(['id' => $eventId])->first();
        if (!$eventModel) {
            return redirect(route('events'))->withErrors([trans('global.InvalidInput')]);
        }
        $data = ['eventModel' => $eventModel];
        return view('participants', $data);
    }

    public function getParticipants($eventId)
    {
        if(!Gate::allows(AuthServiceProvider::MANAGE_PARTICIPANTS)){
            return response()->json(['status' => false, 'message' => trans('global.AccessDeniedAction'), 'code'=>-504]);
        }
        $eventModel = EventModel::query()->where(['id' => $eventId])->first();
        if (!$eventModel) {
            return response()->json(['status' => false, 'message' => trans('global.InvalidInput')]);
        }

        $query = EventParticipant::query()->where('event_id', $eventId)->latest();
        $datatable = DataTables::of($query)->addIndexColumn();
        $datatable->addColumn('status_title', function ($row) {
            return $row->status_title;
        });
        $datatable->addColumn('participant_name', function ($row) {
            return $row->participant_name;
        });
        return $datatable
            ->addColumn('action', function ($row) {
                return (new ParticipantsListActions($row))->render();
            })->rawColumns(['action'])->make(true);
    }

    public function start(Request $request)
    {
        if(!Gate::allows(AuthServiceProvider::START_EVENT)){
            return response()->json(['status' => false, 'message' => trans('global.AccessDeniedAction'), 'code'=>-504]);
        }
        $eventId = $request->input('event_id');
        if(!$eventId){
            return response()->json(['status' => false, 'message' => trans('global.InvalidInput')]);
        }

        /** @var EventModel $eventModel */
        $eventModel = EventModel::query()->where(['id' => $eventId])->first();
        if (!$eventModel) {
            return response()->json(['status' => false, 'message' => trans('global.InvalidInput')]);
        }
        if (!$eventModel->startAble()) {
            return response()->json(['status' => false, 'message' => trans('global.CantStartEvent')]);
        }
        $eventModel->start_date = Carbon::now();
        $eventModel->start_by = Auth::id();
        $eventModel->save();
        return response()->json(['status'=>true, 'message'=>trans('global.SuccessAction')]);
    }


    public function stop(Request $request)
    {
        if(!Gate::allows(AuthServiceProvider::START_EVENT)){
            return response()->json(['status' => false, 'message' => trans('global.AccessDeniedAction'), 'code'=>-504]);
        }
        $eventId = $request->input('event_id');
        if(!$eventId){
            return response()->json(['status' => false, 'message' => trans('global.InvalidInput')]);
        }

        /** @var EventModel $eventModel */
        $eventModel = EventModel::query()->where(['id' => $eventId])->first();
        if (!$eventModel) {
            return response()->json(['status' => false, 'message' => trans('global.InvalidInput')]);
        }
        if (!$eventModel->expireAble()) {
            return response()->json(['status' => false, 'message' => trans('global.CantStopEvent')]);
        }
        $eventModel->expired_date = Carbon::now();
        $eventModel->expired_by = Auth::id();
        $eventModel->save();
        return response()->json(['status'=>true, 'message'=>trans('global.SuccessAction')]);
    }
}
