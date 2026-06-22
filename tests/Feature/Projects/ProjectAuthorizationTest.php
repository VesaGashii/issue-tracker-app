<?php

namespace Tests\Feature\Projects;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_from_project_management_pages(): void
    {
        $project = Project::factory()->create();

        $this->get(route('projects.create'))->assertRedirect(route('login'));
        $this->get(route('projects.edit', $project))->assertRedirect(route('login'));
    }

    public function test_only_the_owner_can_edit_or_delete_a_project(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $project = Project::factory()->for($owner, 'owner')->create();

        $this->actingAs($otherUser)
            ->get(route('projects.edit', $project))
            ->assertForbidden();

        $this->actingAs($otherUser)
            ->delete(route('projects.destroy', $project))
            ->assertForbidden();

        $this->actingAs($owner)
            ->get(route('projects.edit', $project))
            ->assertOk();
    }
}
