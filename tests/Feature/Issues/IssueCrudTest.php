<?php

namespace Tests\Feature\Issues;

use App\Enums\IssuePriority;
use App\Enums\IssueStatus;
use App\Models\Comment;
use App\Models\Issue;
use App\Models\Project;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IssueCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_issues_are_listed_with_their_project_tags_and_comment_count(): void
    {
        $issue = Issue::factory()->create();
        $tag = Tag::factory()->create();
        $issue->tags()->attach($tag);
        Comment::factory()->count(2)->for($issue)->create();

        $this->get(route('issues.index'))
            ->assertOk()
            ->assertSee($issue->title)
            ->assertSee($issue->project->name)
            ->assertSee($tag->name)
            ->assertSee('2 comments');
    }

    public function test_issues_can_be_filtered_by_status_priority_and_tag(): void
    {
        $wantedTag = Tag::factory()->create();
        $wanted = Issue::factory()->create([
            'status' => IssueStatus::Open,
            'priority' => IssuePriority::High,
        ]);
        $wanted->tags()->attach($wantedTag);

        $other = Issue::factory()->create([
            'status' => IssueStatus::Closed,
            'priority' => IssuePriority::Low,
        ]);

        $this->get(route('issues.index', [
            'status' => IssueStatus::Open->value,
            'priority' => IssuePriority::High->value,
            'tag' => $wantedTag->id,
        ]))
            ->assertOk()
            ->assertSee($wanted->title)
            ->assertDontSee($other->title);
    }

    public function test_an_issue_can_be_created(): void
    {
        $project = Project::factory()->create();

        $response = $this->post(route('issues.store'), [
            'project_id' => $project->id,
            'title' => 'Login button does not work',
            'description' => 'The submit button is unresponsive on Safari.',
            'status' => IssueStatus::Open->value,
            'priority' => IssuePriority::High->value,
            'due_date' => '2026-07-15',
        ]);

        $issue = Issue::query()->sole();

        $response->assertRedirect(route('issues.show', $issue));
        $this->assertSame('Login button does not work', $issue->title);
        $this->assertTrue($issue->project->is($project));
    }

    public function test_issue_fields_are_validated(): void
    {
        $this->post(route('issues.store'), [
            'project_id' => 999,
            'title' => '',
            'description' => '',
            'status' => 'waiting',
            'priority' => 'critical',
            'due_date' => 'not-a-date',
        ])->assertSessionHasErrors([
            'project_id',
            'title',
            'description',
            'status',
            'priority',
            'due_date',
        ]);
    }

    public function test_an_issue_can_be_viewed_and_updated(): void
    {
        $issue = Issue::factory()->create();
        $newProject = Project::factory()->create();

        $this->get(route('issues.show', $issue))
            ->assertOk()
            ->assertSee($issue->title);

        $this->put(route('issues.update', $issue), [
            'project_id' => $newProject->id,
            'title' => 'Updated issue title',
            'description' => 'Updated issue description.',
            'status' => IssueStatus::InProgress->value,
            'priority' => IssuePriority::Medium->value,
            'due_date' => null,
        ])->assertRedirect(route('issues.show', $issue));

        $this->assertDatabaseHas('issues', [
            'id' => $issue->id,
            'project_id' => $newProject->id,
            'title' => 'Updated issue title',
            'status' => IssueStatus::InProgress->value,
        ]);
    }

    public function test_an_issue_can_be_deleted(): void
    {
        $issue = Issue::factory()->create();
        $project = $issue->project;

        $this->delete(route('issues.destroy', $issue))
            ->assertRedirect(route('projects.show', $project));

        $this->assertModelMissing($issue);
    }
}
