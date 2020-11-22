<?php

namespace App\Http\Controllers;

use App\OrganUser;
use App\OrganUserRole;
use App\Role;
use App\User;
use App\View\Partials\UserRolesListActions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class RolesController extends Controller
{
    public function index($organUserId)
    {
        $data = [];
        $data['forUserOrgan'] = OrganUser::query()->find($organUserId);
        return view('roles', $data);
    }

    public function getUserList($organUserId)
    {
        $query = OrganUserRole::query()->latest()->where('organ_user_id', $organUserId);
        $datatable = DataTables::of($query)->addIndexColumn();
        $datatable->addColumn('role_name', function ($row) {
            return $row->role_name;
        });
        return $datatable
            ->addColumn('action', function ($row) {
                return (new UserRolesListActions($row))->render();
            })->rawColumns(['action'])->make(true);
    }

    public function addUserRole($userOrganId, Request $request)
    {
        $roleId = $request->input('role_id');
        if (!$roleId || !($role=Role::query()->find($roleId))) {
            return response()->json(['status' => false, 'message' => trans('global.InvalidInput')]);
        }
        /** @var Role $role */

        $found = false;
        $organUserRole = OrganUserRole::createOrUpdate([
            'organ_user_id' => $userOrganId,
            'role_id' => $roleId,
            'role_name' => $role->name,
            'created_by' => Auth::id()
        ], function ($builder) use($userOrganId, $roleId){
            return $builder->where(['organ_user_id'=>$userOrganId, 'role_id'=>$roleId]);
        }, null, $found);

        return response()->json([
            'status'=>$organUserRole && !$found,
            'message'=> $organUserRole ? ($found ? trans('global.DuplicateData') : trans('global.SuccessAction')) : trans('global.ErrorAction'),
            'data'=>$organUserRole
        ]);
    }
}
