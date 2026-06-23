<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_demo_data_can_be_seeded(): void
    {
        $this->seed();

        $this->assertDatabaseCount('projects', 3);
        $this->assertDatabaseCount('issues', 12);
        $this->assertDatabaseCount('tags', 5);
        $this->assertDatabaseCount('comments', 24);
        $this->assertDatabaseCount('issue_user', 24);

        $this->assertDatabaseHas('projects', ['name' => 'Customer Portal']);
        $this->assertDatabaseHas('issues', ['title' => 'Login form fails on Safari']);
    }
}
