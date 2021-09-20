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


        Gate::define('admin-management', function ($user){
            return $user->isAdmin();
        });

        //Only admin or owner can manage profile
        Gate::define('manage-profile', function ($user, $profile){
            return $user->isAdmin() || $user->id === $profile->id;
        });

        //Only admin or owner can view profile
        Gate::define('view-profile', function ($user, $profile){
            return $user->isAdmin() || $user->id === $profile->id;
        });

        //Only post author can manage post
        Gate::define('edit-post', function ($user, $post){
            return $user->id === $post->user_id;
        });

        Gate::define('delete-post', function ($user, $post){
            return $user->isAdmin() || $user->id === $post->user_id;
        });


        //Only comment author can manage post
        Gate::define('edit-comment', function ($user, $comment){
            return $user->id === $comment->user_id;
        });

        Gate::define('delete-comment', function ($user, $comment){
            return $user->isAdmin() || $user->id === $comment->user_id;
        });
    }
}
