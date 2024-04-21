<?php

namespace Tests\Unit\Listeners;


use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use App\Events\PostCreated;
use Illuminate\Support\Facades\Queue;
use Illuminate\Events\CallQueuedListener;
use Illuminate\Support\Facades\Notification;
use App\Listeners\NotifyFavoriteUsersOfNewPost;
use App\Notifications\NewPostCreatedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NotifyFavoriteUsersOfNewPostTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function listener_will_notifiy_all_users_who_favorited_post_user()
    {
        // Given we have a post
        $postUser = User::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $postUser->id,
        ]);

        // factory 5 users who have favorited the post user
        for ($i = 0; $i < 5; $i++) {
            User::factory()
                ->create([
                    'email' => 'notifiable-user' . $i . '@example.com',
                ])
                ->each(function ($user) use ($postUser) {
                    $user->favorites()->create([
                        'favoriteable_id'   => $postUser->id,
                        'favoriteable_type' => User::class,
                    ]);
                });
        }

        // favtory 5 users who have not favorited the user
        for ($i = 0; $i <5 ; $i++) {
            User::factory()
                ->create([
                    'email' => 'non-notifiable-user' . $i . '@example.com',
                ]);
        }

        Notification::fake();

        event(new PostCreated($postUser, $post));

        $notifiableUsers = User::where('email', 'like', 'notifiable-user%')->get();
        Notification::assertSentTo(
            [$notifiableUsers], NewPostCreatedNotification::class
        );
        $nonNotifiableUsers = User::where('email', 'like', 'non-notifiable-user%')->get();
        Notification::assertNotSentTo(
            [$nonNotifiableUsers], NewPostCreatedNotification::class
        );
    }

    /** @test */
    public function assert_listener_is_queued()
    {
        Queue::fake();

        event(new PostCreated(User::factory()->create(), Post::factory()->create()));

        Queue::assertPushed(CallQueuedListener::class, function ($job) {
            return $job->class == NotifyFavoriteUsersOfNewPost::class;
        });
    }

}
