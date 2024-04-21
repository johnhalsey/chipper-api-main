<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\NewPostCreatedNotification;

class NotifyFavoriteUsersOfNewPost implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        // find any users who have favorited the $event->user
        // and send them an email notification about the new post
        $event->user->favoritables->each(function ($favorite) use ($event) {
            $favorite->user->notify(new NewPostCreatedNotification($event->user, $event->post));
        });
    }
}
