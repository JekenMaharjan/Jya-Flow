<?php

test('the application returns a successful response', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

test('Perform Sum of 2 numbers', function () {
    $result = 2 + 2;

    expect($result)->toBe(4);
});