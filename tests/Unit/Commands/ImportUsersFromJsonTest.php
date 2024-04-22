<?php

namespace Tests\Unit\Commands;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ImportUsersFromJsonTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function command_requires_arguments()
    {
        $this->expectExceptionMessage('Not enough arguments (missing: "url, limit").');

        $this->artisan('chipper:import-users-from-json');
    }

    /** @test */
    public function command_will_create_users_from_url_json()
    {
        $this->assertDatabaseCount('users', 0);

        $this->artisan('chipper:import-users-from-json https://jsonplaceholder.typicode.com/users 5')
            ->assertExitCode(0);

        $this->assertDatabaseCount('users', 5);
    }

    /** @test */
    public function command_will_not_duplicate_users_from_url_json()
    {
        $this->assertDatabaseCount('users', 0);

        $this->artisan('chipper:import-users-from-json https://jsonplaceholder.typicode.com/users 5')
            ->assertExitCode(0);

        $this->assertDatabaseCount('users', 5);

        $this->artisan('chipper:import-users-from-json https://jsonplaceholder.typicode.com/users 10')
            ->expectsOutputToContain('already exists, moving on.')
            ->assertExitCode(0);

        $this->assertDatabaseCount('users', 10);
    }
}
