<?php

namespace Database\Seeders;

use App\Models\Project;
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
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $project = Project::create([
            'title' => 'Project Management System MIS',
            'description' => '<h5><strong>About Laravel</strong></h5><p>Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:</p><ul><li>Simple, fast routing engine.</li><li>Powerful dependency injection container.</li><li>Multiple back-ends for session and cache storage.</li><li>Expressive, intuitive database ORM.</li><li>Database agnostic schema migrations.</li><li>Robust background job processing.</li><li>Real-time event broadcasting.</li></ul><p>Laravel is accessible, powerful, and provides tools required for large, robust applications.</p>',
            'start_date' => now(),
            'end_date' => now()->addDays(30),
            'status' => 2,
        ]);

        $status = $project->statuses()->createMany([
            ['label' => 'Backlog',     'category' => 'backlog',     'color' => '#64748b', 'order' => 1], // Slate Gray
            ['label' => 'Todo',        'category' => 'todo',        'color' => '#0ea5e9', 'order' => 2], // Sky Blue
            ['label' => 'In Progress', 'category' => 'in_progress', 'color' => '#f59e0b', 'order' => 3], // Amber/Orange
            ['label' => 'Done',        'category' => 'done',        'color' => '#10b981', 'order' => 4], // Emerald Green
            ['label' => 'Canceled',    'category' => 'canceled',    'color' => '#ef4444', 'order' => 5], // Soft Red
        ]);
    }
}
