<?php

use App\Models\User;

test('users are redirected to the dashboard when they access a route for another role', function () {
    $user = User::factory()->create();
    $user->forceFill(['role' => 'admin'])->save();

    $response = $this
        ->actingAs($user)
        ->get('/user');

    $response
        ->assertRedirectToRoute('dashboard')
        ->assertSessionHas('error', 'You are not allowed to access this page.');
});