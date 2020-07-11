<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
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

        Gate::define('doctor', function($user){
            return cache()->rememberForever("user.doctor:{$user->id}", function() use ($user){
                return $user->role->slug === 'doctor';
            });
        });

        Gate::define('patient', function($user){
            return cache()->rememberForever("user.patient:{$user->id}", function() use ($user){
                return $user->role->slug === 'patient';
            });
        });

        Gate::define('view-patient', function($user, $profileUser){
            if($user->id === $profileUser->id) return true;
            return !($user->role->slug === 'patient' && $profileUser->role->slug === 'patient');
        });

        Gate::define('edit-profile', function($user, $profileUser){
            return $user->id === $profileUser->id;
        });

        Gate::define('update-profile', function($user, $profileUserId){
            return (int)$user->id === (int)$profileUserId;
        });
    }
}
