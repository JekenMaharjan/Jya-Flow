<?php

namespace Database\Seeders;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $task = new Task;

        $task->user_id = 1;
        $task->title = "NEC Preparation";
        $task->description = "According to the attached files prepare for the NEC examination.";
        $task->filename = " ";
        $task->due_at = "";
        $task->priority = TaskPriority::LOW->value;
        $task->status = TaskStatus::IN_PROGRESS->value;

        $task->save();
    }
}
