<?php

namespace Database\Seeders;

use App\Actions\Task\CreateTaskAction;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FireTask extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first() ?? User::factory()->create();

        for ($i = 1; $i <= 10; $i++) {
            CreateTaskAction::run(
                user: $user,
                data: [
                    'title' => "Seeded Task {$i}",
                    'description' => "This is seeded task number {$i} synced to Firebase.",
                ]
            );
        }
    }
}
