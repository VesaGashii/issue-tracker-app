<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Issue;
use App\Models\Project;
use App\Models\Tag;
use App\Models\User;
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
        User::factory()->create([
            'name' => 'Demo User',
            'email' => 'demo@example.com',
        ]);

        $tags = collect([
            ['name' => 'bug', 'color' => '#ef4444'],
            ['name' => 'feature', 'color' => '#3b82f6'],
            ['name' => 'frontend', 'color' => '#8b5cf6'],
            ['name' => 'backend', 'color' => '#10b981'],
            ['name' => 'urgent', 'color' => '#f59e0b'],
        ])->map(fn (array $tag) => Tag::query()->create($tag));

        Project::factory()
            ->count(3)
            ->create()
            ->each(function (Project $project) use ($tags): void {
                Issue::factory()
                    ->count(8)
                    ->for($project)
                    ->create()
                    ->each(function (Issue $issue) use ($tags): void {
                        $issue->tags()->attach(
                            $tags->random(random_int(1, 3))->pluck('id')
                        );

                        Comment::factory()
                            ->count(random_int(1, 5))
                            ->for($issue)
                            ->create();
                    });
            });
    }
}
