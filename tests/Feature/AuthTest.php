<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// Add 'RefreshDatabase' at top of AuthTest because this test almost always interact with database.
// Without 'RefreshDatabase', it gives leftover data like already existing email and unpredictable results to failure of test.
// Under the wood, it runs 'php artisan migrate:fresh'.

// AAA Pattern:
// 1. Arrange: Set up the initial state (e.g., create a fake user).
// 2. Act: Perform the action you are testing (e.g., send a POST request to /login).  
// 3. Assert: Check that the outcome matches what you expect (e.g., check if the user sees the dashboard).

// REGISTER
test('new users can register successfully', function () {
    // Arrange & Act: Send registration form payload/data
    $response = $this->post('/register', [
        'name' => 'Example',
        'email' => 'Example@gmail.com',
        'password' => 'Example@123',
        'password_confirmation' => 'Example@123',
    ]);

    // Assert: Check response redirect
    $response->assertRedirect('/login');

    // Assert: Check database holds the record
    $this->assertDatabaseHas('users', [
        'email' => 'Example@gmail.com',
    ]);

    // Assert: Check user is currently authenticated in session
    // $this->assertAuthenticated();
});

test('registration fails with invalid email', function () {
    // Arrange & Act: Send registration form payload/data    
    $response = $this->post('/register', [
        'name' => 'Example',
        'email' => 'exampgmail',
        'password' => 'Example@123',
        'password_confirmation' => 'Example@123',
    ]);

    // Assert: Check that sesssion holds validation errors for the email field
    $response->assertSessionHasErrors(['email']);

    // Assert: User was NOT logged in or created
    $this->assertGuest();
});

// LOGIN
test('user can log in with valid credentials', function () {
    // Arrange: Create a user in the database using Factory
    $user = User::factory()->create([
        'email' => 'Example@gmail.com',
        'password' => bcrypt('Example@123'),
    ]);

    // Act: Submit login request
    $response = $this->post('/login', [
        'email' => 'Example@gmail.com',
        'password' => 'Example@123',
    ]);

    // Assert: Verify redirect and auth status
    $response->assertRedirect('/tasks');
    $this->assertAuthenticatedAs($user);
});

test('user cannot log in with invalid password', function () {
    // Arrange: Create a user in the database using Factory
    $user = User::factory()->create([
        'email' => 'Example@gmail.com',
        'password' => bcrypt('Example@123'),
    ]);

    // Act: Submit login request
    $response = $this->post('/login', [
        'email' => 'Example@gmail.com',
        'password' => 'Example3124',
    ]);

    // Assert: Check that session holds validation errors for invalid password
    $response->assertSessionHasErrors(['email']);
    $this->assertGuest();
});

// GUESTS
test('guests are redirected from dashboard to login', function () {
    $response = $this->get('/tasks');

    $response->assertRedirect('/login');
});

test('authenticated users can visit dashboard', function () {
    // Arrage: Create a user in the database using Factory
    $user = User::factory()->create();

    // Act: actingAs() simulates a logged-in session for the request
    $response = $this->actingAs($user)->get('/tasks');

    // Assert: Status
    $response->assertStatus(200);
});

// LOGOUT
test('authenticated user can log out', function () {
    // Arrange: Create a user in the database using Factory
    $user = User::factory()->create();

    // Act: Authenticate user and hit the logout endpoint
    $response = $this->actingAs($user)->post('/logout');

    // Assert: User is redirected to home/login and is now a guest
    $response->assertRedirect('/login');
    $this->assertGuest();
});