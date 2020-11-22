<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\OrganUser;
use App\Providers\AuthServiceProvider;
use App\User;
use App\View\Partials\UsersListActions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class UsersController extends Controller
{
    public function index()
    {
        return view('users');
    }

    public function getList()
    {
        return DataTables::of(User::query()->latest())->addIndexColumn()
            ->addColumn('action', function ($row) {
                return (new UsersListActions($row))->render();
            })->rawColumns(['action'])->make(true);
    }

    public function store(Request $request)
    {
        $name = $request->input('name');
        $email = $request->input('email');
        if (!$name) {
            return response()->json(['status' => false, 'message' => trans('global.InvalidOrganName')]);
        }
        $found = false;
        $user = User::createOrUpdate([
            'name' => $name, 'email' => $email, 'created_by' => Auth::id(), 'password' => bcrypt($email)
        ], function ($builder) use ($email) {
            return $builder->where('email', $email);
        }, null, $found);

        return response()->json([
            'status' => $user && !$found,
            'message' => $user ? ($found ? trans('global.DuplicateData') : trans('global.SuccessAction')) : trans('global.ErrorAction'),
            'data' => $user]);
    }

    public function profile(){
        return view('profile', ['user'=>Auth::user()]);
    }

    public function changeOrgan(Request $request){
        $userOrganId = $request->input('user_organ_id');
        if(!$userOrganId || !($userOrgan = OrganUser::query()->where(['user_id'=>Auth::id(), 'id'=>$userOrganId])->first())){
            return redirect('profile')->withErrors([trans('global.InvalidInput')]);
        }

        /** @var User $user */
        $user = Auth::user();
        $user->setCurrentUserOrganId($userOrganId);
        $user->setCurrentOrganId($userOrgan->organ_id);
        $user->removeRolesNameCache();
        return redirect('profile');
    }
}
