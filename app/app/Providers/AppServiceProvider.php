<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Auth\Access\Response;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        Paginator::useBootstrapFour();

        Gate::before(function(User $user){
            if ($user->role == "moderator")
                return true;
        });

        Gate::define('comment', function(User $user, Comment $comment){
            return ($user->id == $comment->user_id) 
            ? Response::allow()
            : Response::deny('Your don`t moderator');
        });

        Gate::define('comment.accept', function (User $user, Comment $comment): Response|bool {
            return Response::deny('Только модератор может принимать комментарии.');
        });

        Gate::define('comment.reject', function (User $user, Comment $comment): Response|bool {
            return Response::deny('Только модератор может отклонять комментарии.');
        });
    }
}