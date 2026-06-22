<?php

namespace Tests\Feature\Tags;

use App\Models\Issue;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->signIn();
    }

    public function test_tags_are_listed_with_issue_counts(): void
    {
        $tag = Tag::factory()->create();
        $issues = Issue::factory()->count(2)->create();
        $tag->issues()->attach($issues);

        $this->get(route('tags.index'))
            ->assertOk()
            ->assertSee($tag->name)
            ->assertSee('2 issues');
    }

    public function test_a_tag_can_be_created(): void
    {
        $this->post(route('tags.store'), [
            'name' => ' Accessibility ',
            'color' => '#2563eb',
        ])->assertRedirect(route('tags.index'));

        $this->assertDatabaseHas('tags', [
            'name' => 'accessibility',
            'color' => '#2563eb',
        ]);
    }

    public function test_tag_names_must_be_unique(): void
    {
        Tag::factory()->create(['name' => 'bug']);

        $this->post(route('tags.store'), [
            'name' => 'bug',
            'color' => '#ef4444',
        ])->assertSessionHasErrors('name');
    }

    public function test_tag_color_must_be_a_hex_color(): void
    {
        $this->post(route('tags.store'), [
            'name' => 'backend',
            'color' => 'green',
        ])->assertSessionHasErrors('color');
    }
}
