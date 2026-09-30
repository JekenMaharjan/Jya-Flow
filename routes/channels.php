<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Authorize logged-in users to listen to the private 'tasks' channel
Broadcast::channel('tasks', function ($user) {
    return $user !== null;
});