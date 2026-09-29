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

    public function handle(User $user, array $filters = [], int $perPage = 7): array
    {
        // Base query for tasks the user can access
        $baseQuery = Task::where(function ($query) use ($user) {
            $query->where('user_id', $user->id)
                ->orWhere('collaborator_email', $user->email)
                ->orWhere('collaborator_email', 'like', $user->email . ',%')
                ->orWhere('collaborator_email', 'like', '%,' . $user->email)
                ->orWhere('collaborator_email', 'like', '%,' . $user->email . ',%');
        });

        // Calculate counts
        $counts = [
            'total' => (clone $baseQuery)->count(),
            'in_progress' => (clone $baseQuery)->where('status', TaskStatus::IN_PROGRESS->value)->count(),
            'completed' => (clone $baseQuery)->where('status', TaskStatus::COMPLETED->value)->count(),
            'low' => (clone $baseQuery)->where('priority', TaskPriority::LOW->value)->count(),
            'medium' => (clone $baseQuery)->where('priority', TaskPriority::MEDIUM->value)->count(),
            'high' => (clone $baseQuery)->where('priority', TaskPriority::HIGH->value)->count(),
        ];

        // Apply status and priority  filters
        $query = (clone $baseQuery)->with('user');

        if (!empty($filters['status']) && $filters['status'] !== 'all') {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['priority']) && $filters['priority'] !== 'all') {
            $query->where('priority', $filters['priority']);
        }

        // Fetch paginated tasks and append query parameters
        $tasks = $query->latest()->paginate($perPage);

        return [
            'tasks' => $tasks,
            'counts' => $counts,
        ];
    }
}
