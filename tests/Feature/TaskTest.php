<?php

use App\Models\Task;
use App\Models\User;

use function Pest\Laravel\actingAs;

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);


it('fetches tasks for authenticated user', function () {
    // Arrange: Create a user with 3 tasks using TaskFactory
    $user = User::factory()
        ->has(Task::factory()->count(3))
        ->create();
    
    // Act & Assert: Autheticate as the user and hit the API endpoint
    actingAs($user)
        ->getJson('/tasks')
        ->assertStatus(200)
        ->assertJsonCount(3, 'data');
});