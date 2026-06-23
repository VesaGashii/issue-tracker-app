<?php

namespace Tests\Feature\Profile;

use App\Enums\IssueStatus;
use App\Models\Issue;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_view_a_profile(): void
    {
        $this->get(route('profile.show'))->assertRedirect(route('login'));
    }

    public function test_a_user_can_view_their_profile_and_owned_projects(): void
    {
        $user = $this->signIn();
        $project = Project::factory()->for($user, 'owner')->create();
        $issues = Issue::factory()->count(2)->for($project)->create([
            'status' => IssueStatus::Open,
        ]);
        $issues->first()->members()->attach($user);

        $this->get(route('profile.show'))
            ->assertOk()
            ->assertSee($user->name)
            ->assertSee($user->email)
            ->assertSee($project->name)
            ->assertSee('2 issues')
            ->assertSee($issues->first()->title);
    }

    public function test_a_user_can_update_their_name_and_email(): void
    {
        $user = $this->signIn();

        $this->put(route('profile.update'), [
            'name' => 'Alex Updated',
            'email' => 'alex.updated@example.com',
        ])->assertRedirect(route('profile.show'));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Alex Updated',
            'email' => 'alex.updated@example.com',
        ]);
    }

    public function test_a_user_can_change_their_password_with_the_current_password(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->put(route('profile.update'), [
            'name' => $user->name,
            'email' => $user->email,
            'current_password' => 'password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ])->assertRedirect(route('profile.show'));

        $this->assertTrue(Hash::check('new-password', $user->fresh()->password));
    }

    public function test_a_wrong_current_password_is_rejected(): void
    {
        $user = $this->signIn();

        $this->put(route('profile.update'), [
            'name' => $user->name,
            'email' => $user->email,
            'current_password' => 'wrong-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ])->assertSessionHasErrors('current_password');
    }
}
