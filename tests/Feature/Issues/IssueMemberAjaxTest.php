<?php

namespace Tests\Feature\Issues;

use App\Models\Issue;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IssueMemberAjaxTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->signIn();
    }

    public function test_a_member_can_be_assigned_to_an_issue_with_json(): void
    {
        $issue = Issue::factory()->create();
        $member = User::factory()->create();

        $this->postJson(route('issues.members.store', [$issue, $member]))
            ->assertOk()
            ->assertJsonPath('member.id', $member->id);

        $this->assertDatabaseHas('issue_user', [
            'issue_id' => $issue->id,
            'user_id' => $member->id,
        ]);
    }

    public function test_assigning_the_same_member_twice_does_not_duplicate_the_pivot(): void
    {
        $issue = Issue::factory()->create();
        $member = User::factory()->create();

        $this->postJson(route('issues.members.store', [$issue, $member]))->assertOk();
        $this->postJson(route('issues.members.store', [$issue, $member]))->assertOk();

        $this->assertDatabaseCount('issue_user', 1);
    }

    public function test_a_member_can_be_removed_from_an_issue_with_json(): void
    {
        $issue = Issue::factory()->create();
        $member = User::factory()->create();
        $issue->members()->attach($member);

        $this->deleteJson(route('issues.members.destroy', [$issue, $member]))
            ->assertOk()
            ->assertJsonPath('user_id', $member->id);

        $this->assertDatabaseMissing('issue_user', [
            'issue_id' => $issue->id,
            'user_id' => $member->id,
        ]);
    }
}
