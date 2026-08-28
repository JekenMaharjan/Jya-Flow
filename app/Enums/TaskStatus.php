<?php

namespace App\Enums;

enum TaskStatus: string
{
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';

    public function label(): string
    {
        return match($this) {
            self::IN_PROGRESS => 'In Progress',
            self::COMPLETED => 'Task Completed',
        };
    }
}
