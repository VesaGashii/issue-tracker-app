<?php

namespace Tests\Feature\Issues;

use App\Models\Issue;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IssueTagAjaxTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->signIn();
    }

    public function test_a_tag_can_be_attached_to_an_issue_with_json(): void
    {
        $issue = Issue::factory()->create();
        $tag = Tag::factory()->create();

        $this->postJson(route('issues.tags.store', [$issue, $tag]))
            ->assertOk()
            ->assertJsonPath('tag.id', $tag->id);

        $this->assertDatabaseHas('issue_tag', [
            'issue_id' => $issue->id,
            'tag_id' => $tag->id,
        ]);
    }

    public function test_attaching_the_same_tag_twice_does_not_duplicate_the_pivot(): void
    {
        $issue = Issue::factory()->create();
        $tag = Tag::factory()->create();

        $this->postJson(route('issues.tags.store', [$issue, $tag]))->assertOk();
        $this->postJson(route('issues.tags.store', [$issue, $tag]))->assertOk();

        $this->assertDatabaseCount('issue_tag', 1);
    }

    public function test_a_tag_can_be_detached_from_an_issue_with_json(): void
    {
        $issue = Issue::factory()->create();
        $tag = Tag::factory()->create();
        $issue->tags()->attach($tag);

        $this->deleteJson(route('issues.tags.destroy', [$issue, $tag]))
            ->assertOk()
            ->assertJsonPath('tag_id', $tag->id);

        $this->assertDatabaseMissing('issue_tag', [
            'issue_id' => $issue->id,
            'tag_id' => $tag->id,
        ]);
    }
}
