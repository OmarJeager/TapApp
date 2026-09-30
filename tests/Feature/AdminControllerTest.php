<?php

use App\Models\PpmRecord;
use App\Models\User;
use Illuminate\Http\UploadedFile;

it('rejects importing a job ID that already exists', function () {
    $admin = User::factory()->create();
    $admin->forceFill(['role' => 'admin'])->save();
    PpmRecord::create(['job_id' => 'JOB-100']);

    $file = UploadedFile::fake()->createWithContent('records.csv', "job_id\nJOB-100\nJOB-200\n");

    $response = $this->actingAs($admin)->post(route('ppm-records.import'), [
        'file' => $file,
    ]);

    $response
        ->assertRedirectToRoute('ppm-records.index')
        ->assertSessionHas('error', 'Job ID already exists.');
    $this->assertDatabaseCount('ppm_records', 1);
});

it('rejects duplicate job IDs in the same file', function () {
    $admin = User::factory()->create();
    $admin->forceFill(['role' => 'admin'])->save();

    $file = UploadedFile::fake()->createWithContent('records.csv', "job_id\nJOB-100\nJOB-100\n");

    $response = $this->actingAs($admin)->post(route('ppm-records.import'), [
        'file' => $file,
    ]);

    $response
        ->assertRedirectToRoute('ppm-records.index')
        ->assertSessionHas('error', 'Job ID already exists.');
    $this->assertDatabaseCount('ppm_records', 0);
});