<?php

namespace Tests\Feature\Models;

use App\Enums\IssuePriority;
use App\Enums\IssueStatus;
use App\Models\Comment;
use App\Models\Issue;
use App\Models\Project;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IssueTrackerRelationshipsTest extends TestCase
{
    use RefreshDatabase;

    public function test_project_has_many_issues_and_issue_belongs_to_project(): void
    {
        $project = Project::factory()->create();
        $issue = Issue::factory()->for($project)->create();

        $this->assertTrue($project->issues->contains($issue));
        $this->assertTrue($issue->project->is($project));
    }

    public function test_issue_has_comments_and_belongs_to_many_tags(): void
    {
        $issue = Issue::factory()->create();
        $comment = Comment::factory()->for($issue)->create();
        $tag = Tag::factory()->create();

        $issue->tags()->attach($tag);

        $this->assertTrue($issue->comments->contains($comment));
        $this->assertTrue($comment->issue->is($issue));
        $this->assertTrue($issue->tags->contains($tag));
        $this->assertTrue($tag->issues->contains($issue));
    }

    public function test_issue_values_and_dates_are_cast_to_domain_types(): void
    {
        $issue = Issue::factory()->create([
            'status' => IssueStatus::InProgress,
            'priority' => IssuePriority::High,
            'due_date' => '2026-08-15',
        ]);

        $project = Project::factory()->create([
            'start_date' => '2026-06-01',
            'deadline' => '2026-12-01',
        ]);

        $this->assertSame(IssueStatus::InProgress, $issue->status);
        $this->assertSame(IssuePriority::High, $issue->priority);
        $this->assertSame('2026-08-15', $issue->due_date->toDateString());
        $this->assertSame('2026-06-01', $project->start_date->toDateString());
        $this->assertSame('2026-12-01', $project->deadline->toDateString());
    }

    public function test_deleting_a_project_cascades_to_issues_comments_and_pivot_rows(): void
    {
        $project = Project::factory()->create();
        $issue = Issue::factory()->for($project)->create();
        $comment = Comment::factory()->for($issue)->create();
        $tag = Tag::factory()->create();
        $issue->tags()->attach($tag);

        $project->delete();

        $this->assertModelMissing($issue);
        $this->assertModelMissing($comment);
        $this->assertDatabaseMissing('issue_tag', [
            'issue_id' => $issue->id,
            'tag_id' => $tag->id,
        ]);
        $this->assertModelExists($tag);
    }
}
