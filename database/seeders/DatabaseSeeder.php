<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // $this->call([
        //     UserSeeder::class,
        //     TaskSeeder::class,
        // ]);

        // -----------------------------------------------------------

        // // Generates 10 users
        // User::factory(10)->create();

        // // Generates 10 tasks
        // Task::factory(10)->create();

        // Create a known main user to login
        $user = User::factory()->create([
            'name' => 'Jeken Maharjan',
            'email' => 'jeken@gmail.com',
            'password' => bcrypt('jeken@123'),
        ]);

        // Attach tasks specifically to main user
        Task::factory(5)->create([
            'user_id' => $user->id,
        ]);

        // Generates 10 users, and automatically creates 3 tasks for EACH user
        User::factory(10)
            ->has(Task::factory()->count(3))
            ->create();
    }
}
