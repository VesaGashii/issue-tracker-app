<?php

namespace Tests\Feature;

use App\Models\User;
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
        $this->assertDatabaseCount('issue_user', 12);

        $this->assertDatabaseHas('projects', ['name' => 'Customer Portal']);
        $this->assertDatabaseHas('issues', ['title' => 'Login form fails on Safari']);

        $users = User::query()->with('assignedIssues:id')->orderBy('id')->get();

        $this->assertSame([4, 4, 4], $users->map->assignedIssues->map->count()->all());
        $this->assertNotSame(
            $users[0]->assignedIssues->pluck('id')->all(),
            $users[1]->assignedIssues->pluck('id')->all()
        );
        $this->assertNotSame(
            $users[1]->assignedIssues->pluck('id')->all(),
            $users[2]->assignedIssues->pluck('id')->all()
        );
    }
}
