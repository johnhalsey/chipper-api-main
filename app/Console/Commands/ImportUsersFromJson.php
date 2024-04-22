<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class ImportUsersFromJson extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chipper:import-users-from-json {url} {limit}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import users from a JSON url.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $url = $this->argument('url');
        $limit = $this->argument('limit');

        $this->info("Importing users from $url with a limit of $limit.");

        // get JSON from url
        $userJson = json_decode(file_get_contents($url), true);
        for ($i = 0; $i < $limit; $i++) {
            $user = $userJson[$i];
            $this->info("Importing user: {$user['name']} with email: {$user['email']}");

            if (User::where('email', $user['email'])->exists()) {
                $this->info("User with email: {$user['email']} already exists, moving on.");
                continue;
            }
            User::create([
                'name'     => $user['name'],
                'email'    => $user['email'],
                'password' => Hash::make(Str::password(16)),
            ]);
        }
    }
}
