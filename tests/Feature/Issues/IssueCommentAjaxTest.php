<?php

namespace Tests\Feature\Issues;

use App\Models\Comment;
use App\Models\Issue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IssueCommentAjaxTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->signIn();
    }

    public function test_comments_are_returned_as_paginated_html_json(): void
    {
        $issue = Issue::factory()->create();
        $comments = Comment::factory()->count(6)->for($issue)->create();

        $response = $this->getJson(route('issues.comments.index', $issue));

        $response->assertOk()
            ->assertJsonStructure(['html', 'next_page_url'])
            ->assertSee($comments->last()->author_name, false)
            ->assertDontSee($comments->first()->author_name, false);

        $this->assertNotNull($response->json('next_page_url'));
    }

    public function test_a_comment_can_be_added_with_json(): void
    {
        $issue = Issue::factory()->create();

        $this->postJson(route('issues.comments.store', $issue), [
            'author_name' => 'Vesa',
            'body' => 'I can reproduce this issue.',
        ])
            ->assertCreated()
            ->assertJsonStructure(['message', 'html'])
            ->assertSee('Vesa', false)
            ->assertSee('I can reproduce this issue.', false);

        $this->assertDatabaseHas('comments', [
            'issue_id' => $issue->id,
            'author_name' => 'Vesa',
            'body' => 'I can reproduce this issue.',
        ]);
    }

    public function test_comment_validation_errors_are_returned_as_json(): void
    {
        $issue = Issue::factory()->create();

        $response = $this->postJson(route('issues.comments.store', $issue), [
            'author_name' => '',
            'body' => '',
        ]);

        $this->assertSame(422, $response->status());
        $this->assertArrayHasKey('author_name', $response->json('errors'));
        $this->assertArrayHasKey('body', $response->json('errors'));
    }
}
