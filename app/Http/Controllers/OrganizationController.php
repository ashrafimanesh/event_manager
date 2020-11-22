<?php

namespace App\Http\Controllers;

use App\Organ;
use App\OrganUser;
use App\OrganUserRole;
use App\Role;
use App\User;
use App\View\Partials\OrgansListActions;
use App\View\Partials\UserOrgansListActions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class OrganizationController extends Controller
{
    public function index($userId = null)
    {
        /** @var User $user */
        $user = Auth::user();
        $data = [];

        /**
         * If user is not admin then we should filter only user organization
         */
        if(!$user->isAdmin()){
            $data['forUser'] = $user;
        }
        elseif ($userId) {
            $data['forUser'] = User::query()->find($userId);
        }
        return view('organs', $data);
    }

    public function getList()
    {
        $query = Organ::query()->latest();
        $datatable = DataTables::of($query)->addIndexColumn();
        $datatable->addColumn('users_count', function ($row) {
            return $row->users_count;
        });
        return $datatable
            ->addColumn('action', function ($row) {
                return (new OrgansListActions($row))->render();
            })->rawColumns(['action'])->make(true);
    }

    public function getUserList($userId)
    {
        $query = OrganUser::query()->latest()->where('user_id', $userId);
        $datatable = DataTables::of($query)->addIndexColumn();
        $datatable->addColumn('organ_name', function ($row) {
            return $row->organ_name;
        });
        return $datatable
            ->addColumn('action', function ($row) use ($userId) {
                return (new UserOrgansListActions($row, $userId))->render();
            })->rawColumns(['action'])->make(true);
    }

    public function addUserOrgan($userId, Request $request)
    {
        $organId = $request->input('organ_id');
        if (!$organId) {
            return response()->json(['status' => false, 'message' => trans('global.InvalidInput')]);
        }
        $found = false;
        DB::beginTransaction();
        $organUser = OrganUser::createOrUpdate([
            'user_id' => $userId,
            'organ_id' => $organId,
            'created_by' => Auth::id()
        ], function ($builder) use($userId, $organId){
            return $builder->where(['user_id'=>$userId, 'organ_id'=>$organId]);
        }, null, $found);
        $organUserRole = null;
        /** @var Role $role */
        $role = Role::query()->where('name', Role::PARTICIPANT)->first();
        if($organUser && $role){
            $userOrganId = $organUser->id;
            $roleId = $role->id;
            $organUserRole = OrganUserRole::createOrUpdate([
                'organ_user_id' => $userOrganId,
                'role_id' => $roleId,
                'role_name' => $role->name,
                'created_by' => Auth::id()
            ], function ($builder) use($userOrganId, $roleId){
                return $builder->where(['organ_user_id'=>$userOrganId, 'role_id'=>$roleId]);
            }, null);

        }
        DB::commit();

        return response()->json([
            'status'=>$organUser && !$found && $organUserRole,
            'message'=> $organUser && $organUserRole ? ($found ? trans('global.DuplicateData') : trans('global.SuccessAction')) : trans('global.ErrorAction'),
            'data'=>$organUser
        ]);
    }

    public function store(Request $request){
        $name = $request->input('name');
        if (!$name) {
            return response()->json(['status' => false, 'message' => trans('global.InvalidOrganName')]);
        }

        $organ = Organ::createOrUpdate([
            'name'=>$name, 'created_by'=>Auth::id()
        ], function($builder)use($name){
            return $builder->where('name', $name);
        }, null, $found);

        return response()->json([
            'status'=>$organ && !$found,
            'message'=> $organ ? ($found ? trans('global.DuplicateData') : trans('global.SuccessAction')) : trans('global.ErrorAction'),
            'data'=>$organ]);
    }
}
