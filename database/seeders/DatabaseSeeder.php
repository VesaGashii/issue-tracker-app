<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Issue;
use App\Models\Tag;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = collect([
            ['name' => 'Alex Morgan', 'email' => 'alex@example.com'],
            ['name' => 'Sam Rivera', 'email' => 'sam@example.com'],
            ['name' => 'Jordan Lee', 'email' => 'jordan@example.com'],
        ])->map(fn (array $user) => User::factory()->create($user));

        $tags = collect([
            ['name' => 'bug', 'color' => '#ef4444'],
            ['name' => 'feature', 'color' => '#3b82f6'],
            ['name' => 'frontend', 'color' => '#8b5cf6'],
            ['name' => 'backend', 'color' => '#10b981'],
            ['name' => 'urgent', 'color' => '#f59e0b'],
        ])->map(fn (array $tag) => Tag::query()->create($tag));

        $projects = [
            [
                'name' => 'Customer Portal',
                'description' => 'A self-service portal where customers can manage their account, invoices, and support requests.',
                'start_date' => now()->subWeeks(3),
                'deadline' => now()->addMonths(3),
                'issues' => [
                    ['title' => 'Login form fails on Safari', 'description' => 'Customers using Safari sometimes remain on the login page after submitting valid credentials.', 'status' => 'in_progress', 'priority' => 'high', 'tags' => ['bug', 'frontend', 'urgent']],
                    ['title' => 'Add invoice download history', 'description' => 'Show the date and user for each downloaded invoice in the billing area.', 'status' => 'open', 'priority' => 'medium', 'tags' => ['feature', 'backend']],
                    ['title' => 'Improve mobile navigation', 'description' => 'The account navigation takes too much vertical space on smaller screens.', 'status' => 'open', 'priority' => 'medium', 'tags' => ['frontend']],
                    ['title' => 'Fix password reset email link', 'description' => 'Reset links should remain valid for the configured expiration period.', 'status' => 'closed', 'priority' => 'high', 'tags' => ['bug', 'backend']],
                ],
            ],
            [
                'name' => 'Internal Help Desk',
                'description' => 'An internal tool for receiving, assigning, and tracking requests from company departments.',
                'start_date' => now()->subMonth(),
                'deadline' => now()->addMonths(2),
                'issues' => [
                    ['title' => 'Add priority sorting to the queue', 'description' => 'Agents need to see high-priority requests at the top of the unassigned queue.', 'status' => 'in_progress', 'priority' => 'high', 'tags' => ['feature', 'backend']],
                    ['title' => 'Request form loses attachments', 'description' => 'Attachments disappear when another validation field fails on the request form.', 'status' => 'open', 'priority' => 'high', 'tags' => ['bug', 'frontend']],
                    ['title' => 'Create a compact ticket card', 'description' => 'Add a denser ticket card option for agents working on smaller laptop screens.', 'status' => 'open', 'priority' => 'low', 'tags' => ['feature', 'frontend']],
                    ['title' => 'Archive resolved requests', 'description' => 'Move requests closed for more than 90 days into an archived view.', 'status' => 'closed', 'priority' => 'low', 'tags' => ['backend']],
                ],
            ],
            [
                'name' => 'Marketing Website',
                'description' => 'The public website refresh covering landing pages, case studies, and lead generation forms.',
                'start_date' => now()->subWeeks(2),
                'deadline' => now()->addMonths(1),
                'issues' => [
                    ['title' => 'Contact form needs spam protection', 'description' => 'Add server-side protection without making the form difficult for real visitors.', 'status' => 'open', 'priority' => 'high', 'tags' => ['feature', 'backend', 'urgent']],
                    ['title' => 'Case study images are too large', 'description' => 'Several images exceed one megabyte and noticeably slow down the case study pages.', 'status' => 'in_progress', 'priority' => 'medium', 'tags' => ['bug', 'frontend']],
                    ['title' => 'Add social sharing metadata', 'description' => 'Add page-specific Open Graph metadata for the main landing pages.', 'status' => 'open', 'priority' => 'medium', 'tags' => ['feature', 'frontend']],
                    ['title' => 'Correct footer links', 'description' => 'The privacy and careers links point to outdated URLs.', 'status' => 'closed', 'priority' => 'low', 'tags' => ['bug', 'frontend']],
                ],
            ],
        ];

        foreach ($projects as $projectIndex => $projectData) {
            $issues = $projectData['issues'];
            unset($projectData['issues']);

            $project = $users[$projectIndex]->ownedProjects()->create($projectData);

            foreach ($issues as $index => $issueData) {
                $tagNames = $issueData['tags'];
                unset($issueData['tags']);

                $issue = Issue::query()->create([
                    ...$issueData,
                    'project_id' => $project->id,
                    'due_date' => CarbonImmutable::today()->addDays(10 + ($index * 8)),
                ]);

                $issue->tags()->attach(
                    $tags->whereIn('name', $tagNames)->pluck('id')
                );
                $issue->members()->attach([
                    $users[$index % $users->count()]->id,
                    $users[($index + 1) % $users->count()]->id,
                ]);

                Comment::factory()
                    ->count(2)
                    ->for($issue)
                    ->create();
            }
        }
    }
}
