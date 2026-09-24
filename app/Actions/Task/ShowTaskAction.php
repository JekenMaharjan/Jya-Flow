<?php

namespace App\Actions\Task;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\User;
use Kreait\Firebase\Contract\Firestore as ContractFirestore;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowTaskAction
{
    use AsAction;

    public function __construct(protected ContractFirestore $firestore)
    {
        //
    }

    public function handle(User $user, array $filters = []): array
    {
        // Query tasks owned by user or where user is listed as a collaborator
        $accessibleTasks = Task::query()
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere('collaborator_email', $user->email)
                    ->orWhere('collaborator_email', 'like', $user->email . ',%')
                    ->orWhere('collaborator_email', 'like', '%,' . $user->email)
                    ->orWhere('collaborator_email', 'like', '%,' . $user->email . ',%');
            });

        // Compute counts from accessible tasks base query
        $counts = [
            'total' => (clone $accessibleTasks)->count(),
            'in_progress' => (clone $accessibleTasks)->where('status', TaskStatus::IN_PROGRESS->value)->count(),
            'completed' => (clone $accessibleTasks)->where('status', TaskStatus::COMPLETED->value)->count(),
            'low' => (clone $accessibleTasks)->where('priority', TaskPriority::LOW->value)->count(),
            'medium' => (clone $accessibleTasks)->where('priority', TaskPriority::MEDIUM->value)->count(),
            'high' => (clone $accessibleTasks)->where('priority', TaskPriority::HIGH->value)->count(),
        ];

        // Apply filters
        $query = (clone $accessibleTasks)->with('user');

        // Status filter (Array checks using !empty)
        $query->when(
            ! empty($filters['status']) && $filters['status'] !== 'all',
            fn ($q) => $q->where('status', $filters['status'])
        );

        // Priority filter
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
