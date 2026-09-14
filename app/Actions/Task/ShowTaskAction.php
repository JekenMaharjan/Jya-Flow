<?php

namespace App\Actions\Task;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowTaskAction
{
    use AsAction;

    public function handle(User $user, array $filters = []): array
    {
        // Calculate counts using SQL dierectly owned by authenticated user
        $counts = [
            'total'       => $user->tasks()->count(),
            'in_progress' => $user->tasks()->where('status', TaskStatus::IN_PROGRESS->value ?? 'in_progress')->count(),
            'completed'   => $user->tasks()->where('status', TaskStatus::COMPLETED->value ?? 'completed')->count(),
            'low'         => $user->tasks()->where('priority', TaskPriority::LOW->value ?? 'low')->count(),
            'medium'      => $user->tasks()->where('priority', TaskPriority::MEDIUM->value ?? 'medium')->count(),
            'high'        => $user->tasks()->where('priority', TaskPriority::HIGH->value ?? 'high')->count(),
        ];

        // Build filtered tasks query
        $query = $user->tasks()->with('user');

        // Status filter (Array checks using !empty)
        $query->when(
            ! empty($filters['status']) && $filters['status'] !== 'all',
            fn ($q) => $q->where('status', $filters['status'])
        );

        // Priority filter (Array checks using !empty)
        $query->when(
            ! empty($filters['priority']) && $filters['priority'] !== 'all',
            fn ($q) => $q->where('priority', $filters['priority'])
        );

        // Fetch paginated tasks and append query parameters
        $tasks = $query->latest()->paginate(5)->withQueryString();

        return [
            'tasks' => $tasks,
            'counts' => $counts,
        ];
    }
}
