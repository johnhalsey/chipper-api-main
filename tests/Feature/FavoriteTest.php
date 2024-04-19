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
            'user_id'          => $user->id,
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
            'user_id'          => $user->id,
        ]);

        $this->actingAs($user)
            ->deleteJson(route('posts.favorites.destroy', ['post' => $post]))
            ->assertNoContent();

        $this->assertDatabaseMissing('favorites', [
            'favoriteable_id'   => $post->id,
            'favoriteable_type' => Post::class,
            'user_id'          => $user->id,
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
            'user_id'          => $user->id,
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
            'user_id'          => $user->id,
        ]);

        $this->actingAs($user)
            ->deleteJson(route('users.favorites.destroy', ['user' => $user2]))
            ->assertNoContent();

        $this->assertDatabaseMissing('favorites', [
            'favoriteable_id'   => $user2->id,
            'favoriteable_type' => User::class,
            'user_id'          => $user->id,
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
}
