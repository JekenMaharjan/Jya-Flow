<?php

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\User;

use function Pest\Laravel\actingAs;
use Illuminate\Support\Str;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\ExpectationFailedException;

uses(RefreshDatabase::class);


// Get Tasks from Authenticated user
it('fetches tasks for authenticated user', function () {
    // Arrange: Create a user with 3 tasks using TaskFactory
    $userA = User::factory()
        ->has(Task::factory()->count(3))
        ->create();

    $userB = User::factory()
        ->has(Task::factory()->count(5))
        ->create();
    
    // Act & Assert: Autheticate as the user and hit the API endpoint
    $this->actingAs($userB)
        ->getJson('/tasks')
        ->assertOk()
        ->assertJsonCount(5, 'tasks.data');
});


// Status Filter
it('returns only completed tasks when status filter is applied', function () {
    // ARRANGE:
    // Create a user
    $user = User::factory()->create();

    // Create task for status = 'in_progress' for the user that was just created
    Task::factory()->count(4)->create([
        'user_id' => $user->id,
        'status' => TaskStatus::IN_PROGRESS->value,
    ]);

    // Create task for status = 'completed' for the user that was just created
    Task::factory()->count(2)->create([
        'user_id' => $user->id,
        'status' => TaskStatus::COMPLETED->value,
    ]);

    // ACT & ASSERT:
    // Check if the returned task are only completed status
    $response = $this->actingAs($user)
        ->getJson('/tasks?status=' . TaskStatus::COMPLETED->value)
        ->assertOk()
        ->assertJsonCount(2, 'tasks.data');

    // Assign tasks json data into '$taskData' variable
    $taskData = $response->json('tasks.data');

    // ASSERT:
    // Loop through each item in the tasks.data and check its status
    foreach ($taskData as $task) {
        expect($task['status'])->toBe(TaskStatus::COMPLETED->value);
    }
});


// Priority Filter
it('returns only high priority tasks when priority filter is applied', function () {
    // ARRANGE:
    $user = User::factory()->create();

    Task::factory()->count(3)->create([
        'user_id' => $user->id,
        'priority' => TaskPriority::LOW->value,
    ]);

    Task::factory()->count(1)->create([
        'user_id' => $user->id,
        'priority' => TaskPriority::MEDIUM->value,
    ]);

    Task::factory()->count(5)->create([
        'user_id' => $user->id,
        'priority' => TaskPriority::HIGH->value,
    ]);

    // ACT & ASSERT:
    $response = $this->actingAs($user)
        ->getJson('/tasks?priority=' . TaskPriority::HIGH->value)
        ->assertOk()
        ->assertJsonCount(5 ,'tasks.data');

    $tasksData = $response->json('tasks.data');

    // ASSERT:
    foreach ($tasksData as $task) {
        expect($task['priority'])->toBe(TaskPriority::HIGH->value);
    }
});


// Combined Filters (Status + Priority)
it('returns combined filters with in_progress status & low priority', function () {
    // ARRANGE:
    $user = User::factory()->create();

    Task::factory()->count(5)->create([
        'user_id' => $user->id,
        'status' => TaskStatus::COMPLETED->value,
        'priority' => TaskPriority::LOW->value,
    ]);

    Task::factory()->count(3)->create([
        'user_id' => $user->id,
        'status' => TaskStatus::IN_PROGRESS->value,
        'priority' => TaskPriority::LOW->value,
    ]);

    Task::factory()->count(2)->create([
        'user_id' => $user->id,
        'status' => TaskStatus::IN_PROGRESS->value,
        'priority' => TaskPriority::HIGH->value,
    ]);

    // Use 'http_build_query' method when there's multiple parameters
    $query = http_build_query([
        'status' => TaskStatus::IN_PROGRESS->value,
        'priority' => TaskPriority::LOW->value,
    ]);

    // ACT & ASSERT:
    $response = $this->actingAs($user)
        ->getJson('/tasks?' . $query)
        ->assertOk()
        ->assertJsonCount(3, 'tasks.data');

    $taskData = $response->json('tasks.data');

    // ASSERT:
    foreach ($taskData as $task) {
        expect([
            $task['status'],
            $task['priority'],
        ])->toBe([
            TaskStatus::IN_PROGRESS->value,
            TaskPriority::LOW->value,
        ]);
    }
});


// Default All Filter
it('returns tasks regardless of state when all status or empty query parameters', function () {
    $user = User::factory()->create();
    
    Task::factory()->count(5)->create([
        'user_id' => $user->id,
    ]);

    $response = $this->actingAs($user)
        ->getJson('/tasks?status=all')
        ->assertOk()
        ->assertJsonCount(5, 'tasks.data');
});


// Task title is required and fails if empty
test('title is required & fails if empty', function () {
    $user = User::factory()->create();

    Task::factory()->create([
        'user_id' => $user->id,
        'title' => '',
    ]);

    $response = $this->actingAs($user)
        ->expect($user['title'])
        ->not->toBeEmpty();
})->throws(ExpectationFailedException::class);


// Fails when 'title' & 'description' exceeds max length
it('fails when title & description exceeds max length', function () {
    // Create a user
    $user = User::factory()->create();

    // Generate strings that exceeds max length
    $tooLongTitle = Str::random(101);  // 101 chars, exceeds max:100
    $tooLongDescription = Str::random(501);  // 501 chars, exceeds max:500

    // Send the POST HTTP request with the invalid payload
    $response = $this->actingAs($user)
        ->postJson('/tasks', [
            'title' => $tooLongTitle,
            'description' => $tooLongDescription,
        ]); 

    // Verify HTTP 422 status and validation error keys
    $response->assertStatus(422)  // Laravel validation error
        ->assertJsonValidationErrors(['title', 'description']);
});

