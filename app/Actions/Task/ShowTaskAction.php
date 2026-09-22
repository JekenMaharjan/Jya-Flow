<?php

namespace App\Actions\Task;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\User;
use Kreait\Firebase\Contract\Firestore as ContractFirestore;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class ShowTaskAction
{
    use AsAction;

    public function __construct(protected ContractFirestore $firestore)
    {
        //
    }

    public function handle(User $user, array $filters = []): array
    {
        $database = $this->firestore->database();
        $allFirestoreTasks = [];

        try {
            // Fetch tasks owned by the user
            $ownedDocuments = $database->collection('tasks')
                ->where('user_id', '=', $user->id)
                ->documents();

            foreach ($ownedDocuments as $doc) {
                if ($doc->exists()) {
                    $allFirestoreTasks[$doc->id()] = array_merge(['id' => $doc->id()], $doc->data());
                }
            }

            // Fetch tasks where the user is a collaborator
            if (! empty($user->email)) {
                $collaboratedDocuments = $database->collection('tasks')
                    ->where('collaborator_email', '=', $user->email)
                    ->documents();

                foreach ($collaboratedDocuments as $doc) {
                    if ($doc->exists()) {
                        $allFirestoreTasks[$doc->id()] = array_merge(['id' => $doc->id()], $doc->data());
                    }
                }
            }
        } catch (Throwable $e) {
            logger()->error("Failed to fetch tasks from Firestore: " . $e->getMessage());
        }

        $accessibleTasks = Task::query()
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere('collaborator_email', $user->email)
                    ->orWhere('collaborator_email', 'like', $user->email . ',%')
                    ->orWhere('collaborator_email', 'like', '%,' . $user->email)
                    ->orWhere('collaborator_email', 'like', '%,' . $user->email . ',%');
            });

        // Calculate counts using SQL dierectly owned by authenticated user
        // $counts = [
        //     'total'       => $user->tasks()->count(),
        //     'in_progress' => $user->tasks()->where('status', TaskStatus::IN_PROGRESS->value ?? 'in_progress')->count(),
        //     'completed'   => $user->tasks()->where('status', TaskStatus::COMPLETED->value ?? 'completed')->count(),
        //     'low'         => $user->tasks()->where('priority', TaskPriority::LOW->value ?? 'low')->count(),
        //     'medium'      => $user->tasks()->where('priority', TaskPriority::MEDIUM->value ?? 'medium')->count(),
        //     'high'        => $user->tasks()->where('priority', TaskPriority::HIGH->value ?? 'high')->count(),
        // ];
        
        $counts = [
            'total' => (clone $accessibleTasks)->count(),

            'in_progress' => (clone $accessibleTasks)
                ->where('status', TaskStatus::IN_PROGRESS->value)
                ->count(),

            'completed' => (clone $accessibleTasks)
                ->where('status', TaskStatus::COMPLETED->value)
                ->count(),

            'low' => (clone $accessibleTasks)
                ->where('priority', TaskPriority::LOW->value)
                ->count(),

            'medium' => (clone $accessibleTasks)
                ->where('priority', TaskPriority::MEDIUM->value)
                ->count(),

            'high' => (clone $accessibleTasks)
                ->where('priority', TaskPriority::HIGH->value)
                ->count(),
        ];

        // Build filtered tasks query
        $query = (clone $accessibleTasks)
        // $query = Task::query()
            ->with('user')
            ->where(function ($query) use ($user) {
                // Tasks owned by the user
                $query->where('user_id', $user->id)

                    // User is the only collaborator
                    ->orWhere('collaborator_email', $user->email)

                    // User is the first collaborator
                    ->orWhere('collaborator_email', 'like', $user->email . ',%')

                    // User is the last collaborator
                    ->orWhere('collaborator_email', 'like', '%,' . $user->email)

                    // User is somewhere in the middle
                    ->orWhere('collaborator_email', 'like', '%,' . $user->email . ',%');
            });

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

        // // Fetch specific user tasks documents from Firebase (Real-time sync)
        // $firestoreTasks = [];

        // try {
        //     $documents = $this->firestore->database()
        //         ->collection('tasks')
        //         ->where('collaborator_email', 'array-contains', $user->firebase_uid)
        //         ->documents();

        //     foreach ($documents as $document) {
        //         if ($document->exists()) {
        //             $firestoreTasks[] = array_merge(['id' => $document->id(), $document->data()]);
        //         }
        //     }
        // } catch (Throwable $e) {
        //     logger()->error("Failed to fetch tasks from Firebase" . $e->getMessage());
        // }

        return [
            'tasks' => $tasks,
            'counts' => $counts,
            // 'tasks' => $firestoreTasks,
            // 'counts' => count($firestoreTasks),
        ];
    }
}
