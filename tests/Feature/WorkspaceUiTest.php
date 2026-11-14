<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class WorkspaceUiTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_and_project_list_show_scoped_progress(): void
    {
        $owner = User::create([
            'name' => 'Design Owner',
            'email' => 'design@example.test',
            'password' => Hash::make('long-test-password'),
        ]);
        $workspace = Workspace::create(['name' => 'Design Space', 'owner_id' => $owner->id]);
        $workspace->users()->attach($owner->id, ['role' => 'owner']);
        $owner->update(['current_workspace_id' => $workspace->id]);

        $project = $workspace->projects()->create([
            'name' => 'Visible Project',
            'creator_id' => $owner->id,
            'status' => 'active',
        ]);
        Task::create(['project_id' => $project->id, 'title' => 'Completed work', 'status' => 'done']);
        Task::create(['project_id' => $project->id, 'title' => 'Upcoming work', 'status' => 'todo']);

        $otherUser = User::create([
            'name' => 'Other Owner',
            'email' => 'other-design@example.test',
            'password' => Hash::make('long-test-password'),
        ]);
        $otherWorkspace = Workspace::create(['name' => 'Other Space', 'owner_id' => $otherUser->id]);
        $otherWorkspace->users()->attach($otherUser->id, ['role' => 'owner']);
        $otherProject = $otherWorkspace->projects()->create([
            'name' => 'Private Project',
            'creator_id' => $otherUser->id,
        ]);
        Task::create(['project_id' => $otherProject->id, 'title' => 'Do not expose', 'status' => 'done']);

        $this->actingAs($owner)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('整体任务进度')
            ->assertSee('50%')
            ->assertSee('Visible Project')
            ->assertDontSee('Private Project');

        $this->actingAs($owner)
            ->get(route('projects.index'))
            ->assertOk()
            ->assertSee('1/2 已完成')
            ->assertSee('50%')
            ->assertDontSee('Private Project');
    }

    public function test_public_landing_page_has_clear_demo_disclosure(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('让复杂的协作')
            ->assertSee('界面示意')
            ->assertSee('暂未提供在线支付');
    }
}