<?php

namespace Tests\Feature\Projects;

use App\Models\Issue;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->signIn();
    }

    public function test_projects_can_be_listed_with_issue_counts(): void
    {
        $project = Project::factory()->create();
        Issue::factory()->count(2)->for($project)->create();

        $this->get(route('projects.index'))
            ->assertOk()
            ->assertSee($project->name)
            ->assertSee('2 issues');
    }

    public function test_a_project_can_be_created(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('projects.store'), [
            'name' => 'Customer portal',
            'description' => 'Build and ship the customer portal.',
            'start_date' => '2026-07-01',
            'deadline' => '2026-09-30',
        ]);

        $project = Project::query()->sole();

        $response->assertRedirect(route('projects.show', $project));
        $this->assertSame('Customer portal', $project->name);
        $this->assertTrue($project->owner->is($user));
    }

    public function test_project_validation_errors_are_returned_to_the_page(): void
    {
        $this->actingAs(User::factory()->create());

        $this->from(route('projects.create'))
            ->post(route('projects.store'), [
                'name' => '',
                'description' => '',
                'start_date' => '2026-08-01',
                'deadline' => '2026-07-01',
            ])
            ->assertRedirect(route('projects.create'))
            ->assertSessionHasErrors(['name', 'description', 'deadline']);
    }

    public function test_a_project_can_be_viewed_with_its_issues(): void
    {
        $project = Project::factory()->create();
        $issue = Issue::factory()->for($project)->create();

        $this->get(route('projects.show', $project))
            ->assertOk()
            ->assertSee($project->name)
            ->assertSee($issue->title);
    }

    public function test_a_project_can_be_updated(): void
    {
        $project = Project::factory()->create();
        $this->actingAs($project->owner);

        $this->put(route('projects.update', $project), [
            'name' => 'Updated project',
            'description' => 'Updated description.',
            'start_date' => null,
            'deadline' => null,
        ])->assertRedirect(route('projects.show', $project));

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'name' => 'Updated project',
        ]);
    }

    public function test_a_project_and_its_issues_can_be_deleted(): void
    {
        $project = Project::factory()->create();
        $this->actingAs($project->owner);
        $issue = Issue::factory()->for($project)->create();

        $this->delete(route('projects.destroy', $project))
            ->assertRedirect(route('projects.index'));

        $this->assertModelMissing($project);
        $this->assertModelMissing($issue);
    }
}
