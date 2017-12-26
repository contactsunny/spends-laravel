<?php

use Illuminate\Database\Seeder;

class AuthTokenTestModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('auth_tokens')->delete();

        \App\Models\AuthToken::create([
            'id' => 1,
            'user_id' => '$2y$10$1rmEA5P6OuPLeBzSRchFFuIBbBTWhhWBYgJRVPeOHMRrI5hnQY5u',
            'auth_token' => '$2y$10$1rmEA5P6OuPLeBzSRchFFuIBbBTWhhWBYgJRVPeOHMRrI5hnQY5u',
        ]);
    }
}
