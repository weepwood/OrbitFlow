<?php
namespace Tests\Feature;

use App\Livewire\ProjectPage;
use App\Livewire\ProjectsPage;
use App\Livewire\TeamPage;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class SaasFlowTest extends TestCase
{
    use RefreshDatabase;

    private function owner(string $email): array
    {
        $user = User::create(['name' => 'Test Owner', 'email' => $email, 'password' => Hash::make('test-password-long')]);
        $workspace = Workspace::create(['name' => 'A Workspace', 'owner_id' => $user->id]);
        $workspace->users()->attach($user->id, ['role' => 'owner']);
        $user->update(['current_workspace_id' => $workspace->id]);
        return [$user, $workspace];
    }

    public function test_registration_creates_an_isolated_workspace(): void
    {
        $this->post('/register', [
            'name' => 'Ada', 'email' => 'ada@example.test', 'workspace_name' => 'Ada Team',
            'password' => 'very-strong-password', 'password_confirmation' => 'very-strong-password',
        ])->assertRedirect('/dashboard');

        $user = User::where('email', 'ada@example.test')->firstOrFail();
        $this->assertAuthenticatedAs($user);
        $this->assertSame('owner', $user->workspaces()->firstOrFail()->pivot->role);
        $this->assertEquals($user->current_workspace_id, $user->workspaces()->firstOrFail()->id);
    }

    public function test_a_user_cannot_view_another_tenants_project(): void
    {
        [$a] = $this->owner('a@example.test');
        [, $bWorkspace] = $this->owner('b@example.test');
        $otherProject = Project::create(['workspace_id' => $bWorkspace->id, 'creator_id' => $bWorkspace->owner_id, 'name' => 'Private project']);
        $this->actingAs($a)->get(route('projects.show', $otherProject))->assertNotFound();
    }

    public function test_project_creation_and_task_creation_are_scoped_to_active_workspace(): void
    {
        [$owner, $workspace] = $this->owner('owner@example.test');
        Livewire::actingAs($owner)->test(ProjectsPage::class)
            ->set('name', 'Prototype')->set('status', 'active')->call('save')->assertHasNoErrors();
        $project = $workspace->projects()->sole();
        $this->assertSame('Prototype', $project->name);

        Livewire::actingAs($owner)->test(ProjectPage::class, ['project' => $project])
            ->set('taskTitle', 'Write requirements')->set('priority', 'high')->call('addTask')->assertHasNoErrors();
        $this->assertSame(1, Task::where('project_id', $project->id)->count());
    }

    public function test_member_cannot_delete_projects_or_manage_members(): void
    {
        [$owner, $workspace] = $this->owner('owner2@example.test');
        $member = User::create(['name' => 'Member', 'email' => 'member@example.test', 'password' => 'test-password-long', 'current_workspace_id' => $workspace->id]);
        $workspace->users()->attach($member->id, ['role' => 'member']);
        $project = $workspace->projects()->create(['name' => 'Keep me', 'creator_id' => $owner->id]);

        Livewire::actingAs($member)->test(ProjectsPage::class)->call('delete', $project->id)->assertStatus(403);
        Livewire::actingAs($member)->test(TeamPage::class)->call('removeMember', $owner->id)->assertStatus(403);
        $this->assertDatabaseHas('projects', ['id' => $project->id]);
    }

    public function test_workspace_switch_requires_membership(): void
    {
        [$owner] = $this->owner('owner3@example.test');
        [, $otherWorkspace] = $this->owner('other@example.test');
        $this->actingAs($owner)->post(route('workspaces.switch'), ['workspace_id' => $otherWorkspace->id])->assertNotFound();
    }
}
