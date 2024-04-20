<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_guest_can_not_favorite_a_post()
    {
        $post = Post::factory()->create();

        $this->postJson(route('posts.favorites.store', ['post' => $post]))
            ->assertStatus(401);
    }

    public function test_a_user_can_favorite_a_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($user)
            ->postJson(route('posts.favorites.store', ['post' => $post]))
            ->assertCreated();

        $this->assertDatabaseHas('favorites', [
            'user_id'           => $user->id,
            'favoriteable_id'   => $post->id,
            'favoriteable_type' => Post::class,
        ]);
    }

    public function test_a_user_can_remove_a_post_from_his_favorites()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($user)
            ->postJson(route('posts.favorites.store', ['post' => $post]))
            ->assertCreated();

        $this->assertDatabaseHas('favorites', [
            'favoriteable_id'   => $post->id,
            'favoriteable_type' => Post::class,
            'user_id'           => $user->id,
        ]);

        $this->actingAs($user)
            ->deleteJson(route('posts.favorites.destroy', ['post' => $post]))
            ->assertNoContent();

        $this->assertDatabaseMissing('favorites', [
            'favoriteable_id'   => $post->id,
            'favoriteable_type' => Post::class,
            'user_id'           => $user->id,
        ]);
    }

    public function test_a_user_can_not_remove_a_non_favorited_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($user)
            ->deleteJson(route('posts.favorites.destroy', ['post' => $post]))
            ->assertNotFound();
    }

    public function test_a_user_can_favorite_a_user()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();

        $this->actingAs($user)
            ->postJson(route('users.favorites.store', ['user' => $user2]))
            ->assertCreated();

        $this->assertDatabaseHas('favorites', [
            'user_id'           => $user->id,
            'favoriteable_id'   => $user2->id,
            'favoriteable_type' => User::class,
        ]);
    }

    public function test_a_user_can_remove_a_user_from_his_favorites()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();

        $this->actingAs($user)
            ->postJson(route('users.favorites.store', ['user' => $user2]))
            ->assertCreated();

        $this->assertDatabaseHas('favorites', [
            'favoriteable_id'   => $user2->id,
            'favoriteable_type' => User::class,
            'user_id'           => $user->id,
        ]);

        $this->actingAs($user)
            ->deleteJson(route('users.favorites.destroy', ['user' => $user2]))
            ->assertNoContent();

        $this->assertDatabaseMissing('favorites', [
            'favoriteable_id'   => $user2->id,
            'favoriteable_type' => User::class,
            'user_id'           => $user->id,
        ]);
    }

    public function test_a_user_can_not_remove_a_non_favorited_user()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();

        $this->actingAs($user)
            ->deleteJson(route('users.favorites.destroy', ['user' => $user2]))
            ->assertNotFound();
    }

    /** @test */
    public function user_can_index_all_their_favorites()
    {
        $user = User::factory()->create();
        $posts = [];
        for ($i = 0; $i < 5; $i++) {
            $posts[$i] = Post::factory()->create();
            $user->favorites()->create([
                'favoriteable_id'   => $posts[$i]->id,
                'favoriteable_type' => Post::class,
            ]);
        }

        $this->assertDatabaseCount('favorites', 5);
        $this->assertCount(5, $user->favorites);

        $users = [];
        for ($i = 0; $i < 3; $i++) {
            $users[$i] = User::factory()->create();
            $user->favorites()->create([
                'favoriteable_id'   => $users[$i]->id,
                'favoriteable_type' => User::class,
            ]);
        }

        $this->assertDatabaseCount('favorites', 8);
        $this->assertCount(3, $user->favorites()->where('favoriteable_type', User::class)->get());

        $response = $this->actingAs($user)
            ->getJson(route('favorites.index'))
            ->assertJsonFragment([
                'data' => [
                    'posts' => [
                        [
                            'id' => $posts[0]->id,
                            'title' => $posts[0]->title,
                            'body' => $posts[0]->body,
                            'user' => [
                                'id' => $posts[0]->user->id,
                                'name' => $posts[0]->user->name,
                            ],
                        ],
                        [
                            'id' => $posts[1]->id,
                            'title' => $posts[1]->title,
                            'body' => $posts[1]->body,
                            'user' => [
                                'id' => $posts[1]->user->id,
                                'name' => $posts[1]->user->name,
                            ],
                        ],
                        [
                            'id' => $posts[2]->id,
                            'title' => $posts[2]->title,
                            'body' => $posts[2]->body,
                            'user' => [
                                'id' => $posts[2]->user->id,
                                'name' => $posts[2]->user->name,
                            ],
                        ],
                        [
                            'id' => $posts[3]->id,
                            'title' => $posts[3]->title,
                            'body' => $posts[3]->body,
                            'user' => [
                                'id' => $posts[3]->user->id,
                                'name' => $posts[3]->user->name,
                            ],
                        ],
                        [
                            'id' => $posts[4]->id,
                            'title' => $posts[4]->title,
                            'body' => $posts[4]->body,
                            'user' => [
                                'id' => $posts[4]->user->id,
                                'name' => $posts[4]->user->name,
                            ],
                        ],
                    ],
                    'users' => [
                        [
                            'id' => $users[0]->id,
                            'name' => $users[0]->name,
                        ],
                        [
                            'id' => $users[1]->id,
                            'name' => $users[1]->name,
                        ],
                        [
                            'id' => $users[2]->id,
                            'name' => $users[2]->name,
                        ],
                    ]
                ],
            ]);
    }
}
