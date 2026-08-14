<?php

use App\Models\User;

test('dashboard index passes summary data to the view', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $this->actingAs($user);

    $response = $this->get('/dashboard');

    $response
        ->assertOk()
        ->assertViewHas('summary')
        ->assertViewHas('chartData');
});
