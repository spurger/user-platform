<?php

namespace App\Providers;

use App\Models\FriendRequest;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('cancel-friend-request', function (User $user, FriendRequest $friendRequest) {
            return $friendRequest->sender_id === $user->getKey();
        });

        Gate::define('respond-to-friend-request', function (User $user, FriendRequest $friendRequest) {
            return $friendRequest->recipient_id === $user->getKey();
        });
    }
}
