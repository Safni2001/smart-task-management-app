<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds for testing.
     */
    public function run(): void
    {
        // Clear existing data
        DB::table('tasks')->truncate();

        // Test dataset with 30 tasks
        $testTasks = [
            [
                'id' => 1,
                'title' => 'System Integration Testing',
                'user_id' => 1,
                'due_date' => '2025-08-14 09:00:00',
                'task_type' => 'Testing',
                'estimated_time' => 3,
                'parent_id' => null,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'title' => 'User Authentication Module',
                'user_id' => 1,
                'due_date' => '2025-07-16 09:15:00',
                'task_type' => 'Development',
                'estimated_time' => 8,
                'parent_id' => null,
                'status' => 'in_progress',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'title' => 'Q3 Business Proposal',
                'user_id' => 1,
                'due_date' => '2025-07-17 11:00:00',
                'task_type' => 'Presentation',
                'estimated_time' => 3,
                'parent_id' => null,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'title' => 'Frontend Components Review',
                'user_id' => 1,
                'due_date' => '2025-07-18 16:45:00',
                'task_type' => 'Development',
                'estimated_time' => 4,
                'parent_id' => 2,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'title' => 'API Integration Testing',
                'user_id' => 1,
                'due_date' => '2025-07-19 13:20:00',
                'task_type' => 'Testing',
                'estimated_time' => 5,
                'parent_id' => 4,
                'status' => 'completed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 6,
                'title' => 'System Architecture Documentation',
                'user_id' => 1,
                'due_date' => '2025-07-20 10:00:00',
                'task_type' => 'Documentation',
                'estimated_time' => 7,
                'parent_id' => null,
                'status' => 'in_progress',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 7,
                'title' => 'New Employee Onboarding',
                'user_id' => 1,
                'due_date' => '2025-07-21 15:30:00',
                'task_type' => 'Training',
                'estimated_time' => 4,
                'parent_id' => null,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 8,
                'title' => 'Payment Gateway Fix',
                'user_id' => 1,
                'due_date' => '2025-07-22 08:45:00',
                'task_type' => 'Development',
                'estimated_time' => 6,
                'parent_id' => 5,
                'status' => 'in_progress',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 9,
                'title' => 'Mobile App Interface Review',
                'user_id' => 1,
                'due_date' => '2025-07-23 12:15:00',
                'task_type' => 'Design',
                'estimated_time' => 3,
                'parent_id' => null,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 10,
                'title' => 'Production Environment Setup',
                'user_id' => 1,
                'due_date' => '2025-07-24 17:00:00',
                'task_type' => 'Deployment',
                'estimated_time' => 8,
                'parent_id' => 8,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 11,
                'title' => 'Competitor Analysis Report',
                'user_id' => 1,
                'due_date' => '2025-07-25 14:20:00',
                'task_type' => 'Research',
                'estimated_time' => 5,
                'parent_id' => 3,
                'status' => 'completed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 12,
                'title' => 'Database Query Optimization',
                'user_id' => 1,
                'due_date' => '2025-07-26 09:30:00',
                'task_type' => 'Development',
                'estimated_time' => 6,
                'parent_id' => 10,
                'status' => 'in_progress',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 13,
                'title' => 'Authentication System Audit',
                'user_id' => 1,
                'due_date' => '2025-07-27 11:45:00',
                'task_type' => 'Security',
                'estimated_time' => 7,
                'parent_id' => null,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 14,
                'title' => 'Marketing Materials Creation',
                'user_id' => 1,
                'due_date' => '2025-07-28 16:10:00',
                'task_type' => 'Content',
                'estimated_time' => 4,
                'parent_id' => 11,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 15,
                'title' => 'Third-party API Integration',
                'user_id' => 1,
                'due_date' => '2025-07-29 13:25:00',
                'task_type' => 'Development',
                'estimated_time' => 9,
                'parent_id' => 12,
                'status' => 'in_progress',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 16,
                'title' => 'Beta Version Feedback',
                'user_id' => 1,
                'due_date' => '2025-07-30 10:50:00',
                'task_type' => 'Testing',
                'estimated_time' => 5,
                'parent_id' => 9,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 17,
                'title' => 'Performance Monitoring Setup',
                'user_id' => 1,
                'due_date' => '2025-07-31 15:40:00',
                'task_type' => 'Analytics',
                'estimated_time' => 6,
                'parent_id' => 15,
                'status' => 'completed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 18,
                'title' => 'Agile Methodology Workshop',
                'user_id' => 1,
                'due_date' => '2025-08-01 09:00:00',
                'task_type' => 'Training',
                'estimated_time' => 8,
                'parent_id' => 7,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 19,
                'title' => 'Data Recovery Plan',
                'user_id' => 1,
                'due_date' => '2025-08-02 12:30:00',
                'task_type' => 'Maintenance',
                'estimated_time' => 4,
                'parent_id' => 13,
                'status' => 'in_progress',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 20,
                'title' => 'Real-time Notifications Development',
                'user_id' => 1,
                'due_date' => '2025-08-03 14:15:00',
                'task_type' => 'Development',
                'estimated_time' => 7,
                'parent_id' => 17,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 21,
                'title' => 'End-to-end Testing',
                'user_id' => 1,
                'due_date' => '2025-08-04 11:20:00',
                'task_type' => 'Testing',
                'estimated_time' => 6,
                'parent_id' => 16,
                'status' => 'completed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 22,
                'title' => 'Cloud Infrastructure Planning',
                'user_id' => 1,
                'due_date' => '2025-08-05 16:45:00',
                'task_type' => 'Planning',
                'estimated_time' => 5,
                'parent_id' => 19,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 23,
                'title' => 'Load Balancing Testing',
                'user_id' => 1,
                'due_date' => '2025-08-06 08:30:00',
                'task_type' => 'Testing',
                'estimated_time' => 8,
                'parent_id' => 20,
                'status' => 'in_progress',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 24,
                'title' => 'System Health Dashboard',
                'user_id' => 1,
                'due_date' => '2025-08-07 13:55:00',
                'task_type' => 'Analytics',
                'estimated_time' => 4,
                'parent_id' => 21,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 25,
                'title' => 'Developer Portal Documentation',
                'user_id' => 1,
                'due_date' => '2025-08-08 10:10:00',
                'task_type' => 'Documentation',
                'estimated_time' => 6,
                'parent_id' => 6,
                'status' => 'completed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 26,
                'title' => 'Auto-scaling Configuration',
                'user_id' => 1,
                'due_date' => '2025-08-09 15:25:00',
                'task_type' => 'Deployment',
                'estimated_time' => 7,
                'parent_id' => 22,
                'status' => 'in_progress',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 27,
                'title' => 'Two-factor Authentication',
                'user_id' => 1,
                'due_date' => '2025-08-10 12:40:00',
                'task_type' => 'Security',
                'estimated_time' => 5,
                'parent_id' => 24,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 28,
                'title' => 'App Store Submission',
                'user_id' => 1,
                'due_date' => '2025-08-11 09:50:00',
                'task_type' => 'Release',
                'estimated_time' => 3,
                'parent_id' => 25,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 29,
                'title' => 'User Behavior Analysis',
                'user_id' => 1,
                'due_date' => '2025-08-12 14:35:00',
                'task_type' => 'Analytics',
                'estimated_time' => 6,
                'parent_id' => 26,
                'status' => 'in_progress',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 30,
                'title' => 'Final Deliverables Review',
                'user_id' => 1,
                'due_date' => '2025-08-13 17:00:00',
                'task_type' => 'Review',
                'estimated_time' => 4,
                'parent_id' => 27,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert all test tasks
        DB::table('tasks')->insert($testTasks);

        $this->command->info('Test dataset with 30 tasks seeded successfully!');
    }
}