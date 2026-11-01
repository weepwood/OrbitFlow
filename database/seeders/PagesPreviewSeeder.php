<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Only run against a fresh, ephemeral SQLite database in the Pages export job.
 * Never use this seeder to populate a production environment.
 */
class PagesPreviewSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment('local') || config('database.default') !== 'sqlite') {
            throw new \RuntimeException('Pages preview seeding requires local environment with SQLite.');
        }

        $password = getenv('ORBITFLOW_PREVIEW_PASSWORD');
        if (! is_string($password) || strlen($password) < 20) {
            throw new \RuntimeException('An ephemeral ORBITFLOW_PREVIEW_PASSWORD is required.');
        }

        $owner = User::create([
            'name' => 'Demo Owner',
            'email' => 'preview@orbitflow.test',
            'password' => Hash::make($password),
        ]);
        $workspace = Workspace::create(['name' => 'Orbit Studio', 'owner_id' => $owner->id]);
        $workspace->users()->attach($owner->id, ['role' => 'owner']);
        $owner->update(['current_workspace_id' => $workspace->id]);

        foreach ([
            ['name' => '产品体验升级', 'description' => '优化关键工作流与产品设计系统', 'status' => 'active', 'tasks' => [['梳理信息架构', 'done'], ['统一 Flux UI 表单', 'in_progress'], ['设计组件验收', 'todo']]],
            ['name' => '设计系统文档', 'description' => '整理设计规范、组件示例与实践案例', 'status' => 'active', 'tasks' => [['组件分类整理', 'done'], ['交互状态检查', 'in_progress']]],
            ['name' => '客户反馈中心', 'description' => '建立反馈收集与优先级评估流程', 'status' => 'planning', 'tasks' => [['需求调研', 'todo']]],
            ['name' => '季度研发计划', 'description' => '围绕稳定性与体验制定研发里程碑', 'status' => 'completed', 'tasks' => [['制定里程碑', 'done'], ['完成评审', 'done']]],
        ] as $item) {
            $project = Project::create([
                'workspace_id' => $workspace->id,
                'creator_id' => $owner->id,
                'name' => $item['name'],
                'description' => $item['description'],
                'status' => $item['status'],
                'due_date' => now()->addDays(14)->toDateString(),
            ]);
            foreach ($item['tasks'] as [$title, $status]) {
                Task::create([
                    'project_id' => $project->id,
                    'assignee_id' => $owner->id,
                    'title' => $title,
                    'status' => $status,
                    'priority' => 'medium',
                    'due_date' => now()->addDays(3)->toDateString(),
                ]);
            }
        }
    }
}
