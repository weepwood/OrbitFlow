<?php
namespace App\Livewire;

use App\Models\User;
use App\Support\WorkspaceContext;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class TeamPage extends Component
{
    public string $email = '';
    public string $role = 'member';
    public bool $showMemberModal = false;

    public function addMember(): void
    {
        abort_unless(WorkspaceContext::canManageMembers(), 403);
        $this->email = Str::lower(trim($this->email));
        $data = $this->validate([
            'email' => ['required', 'email', 'max:255', Rule::exists('users', 'email')],
            'role' => ['required', Rule::in(['admin', 'member'])],
        ], ['email.exists' => '该用户尚未注册，请先邀请其创建账号。']);
        $workspace = WorkspaceContext::current();
        $user = User::where('email', Str::lower($data['email']))->firstOrFail();
        if ($workspace->users()->whereKey($user->id)->exists()) {
            $this->addError('email', '用户已在该工作区。');
            return;
        }
        $workspace->users()->attach($user->id, ['role' => $data['role']]);
        $this->reset('email');
        $this->role = 'member';
        $this->showMemberModal = false;
        session()->flash('success', '成员已加入工作区。');
    }

    public function changeRole(int $id, string $role): void
    {
        abort_unless(WorkspaceContext::canManageMembers(), 403);
        abort_unless(in_array($role, ['admin', 'member'], true), 422);
        $workspace = WorkspaceContext::current();
        abort_if($workspace->owner_id === $id, 403);
        abort_unless($workspace->users()->whereKey($id)->exists(), 404);
        $workspace->users()->updateExistingPivot($id, ['role' => $role]);
        session()->flash('success', '成员权限已更新。');
    }

    public function removeMember(int $id): void
    {
        abort_unless(WorkspaceContext::canManageMembers(), 403);
        $workspace = WorkspaceContext::current();
        abort_if($workspace->owner_id === $id, 403);
        abort_unless($workspace->users()->whereKey($id)->exists(), 404);
        $workspace->users()->detach($id);
        // Do not leave a removed user pointing at a workspace they cannot access.
        $user = User::findOrFail($id);
        if ($user->current_workspace_id === $workspace->id) {
            $user->update(['current_workspace_id' => $user->workspaces()->value('workspaces.id')]);
        }
        session()->flash('success', '成员已移除。');
    }

    public function render()
    {
        $workspace = WorkspaceContext::current();
        return view('livewire.team-page', [
            'workspace' => $workspace,
            'members' => $workspace->users()->orderBy('name')->get(),
            'canManage' => WorkspaceContext::canManageMembers(),
        ]);
    }
}
