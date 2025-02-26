<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Rating;
use App\Models\Recipe;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::factory()
            ->admin()
            ->create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
            ]);
        // Create regular users
        $users = User::factory(10)->create();

        // Create recipes with relationships
        Recipe::factory(20)
            ->recycle($users) // Assign to existing users
            ->has(
                Comment::factory(5)
                    ->recycle($users)
            )
            ->has(
                Rating::factory(3)
                    ->recycle($users)
            )
            ->create();
    }
}
