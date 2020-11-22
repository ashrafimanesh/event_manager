<?php

namespace App\Providers;

use App\EventModel;
use App\EventParticipant;
use App\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    const MANAGE_USERS = 'manage-users';
    const LOGOUT = 'logout';
    const MANAGE_EVENTS = 'manage-evens';
    const JOIN_TO_EVENT = 'join-to-event';
    const MANAGE_PARTICIPANTS = 'manage-participants';
    const START_EVENT = 'start-event';
    const MANAGE_ORGANIZATION = 'manage-organs';
    const MANAGE_ROLES = 'manage-roles';
    const ORGANS_LIST = 'organs-list';

    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define(self::MANAGE_USERS, function(User $user){
            return $user->isAdmin(true);
        });

        Gate::define(self::ORGANS_LIST, function(User $user){
            return !Auth::guest();
        });

        Gate::define(self::MANAGE_ORGANIZATION, function(User $user){
            return $user->isAdmin(true);
        });

        Gate::define(self::MANAGE_ROLES, function(User $user){
            return $user->isAdmin(true);
        });

        Gate::define(self::MANAGE_EVENTS, function(User $user){
            return $user->isAdmin(true);
        });

        Gate::define(self::MANAGE_PARTICIPANTS, function(User $user){
            return $user->isEventOrganizer(true);
        });

        Gate::define(self::START_EVENT, function(User $user){
            return $user->isEventOrganizer(true);
        });

        Gate::define(self::JOIN_TO_EVENT, function(User $user, EventModel $eventModel){
            return !Auth::guest();
        });

        Gate::define(self::LOGOUT, function(User $user){
            return !Auth::guest();
        });
    }
}
