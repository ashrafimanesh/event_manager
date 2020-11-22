<?php

namespace App\Http\Middleware;

use App\Providers\AuthServiceProvider;
use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\Facades\DataTables;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user || !$user->isAdmin(true)) {
            if($request->exists('draw')){
                return DataTables::of(collect([]))->make(true);
            }
            return redirect()->route('504');
        }
        return $next($request);
    }
}
