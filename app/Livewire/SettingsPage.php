<?php
namespace App\Livewire;

use App\Support\WorkspaceContext;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class SettingsPage extends Component
{
    public string $profileName = '';
    public string $workspaceName = '';
    public string $oldPassword = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function mount(): void
    {
        $this->profileName = auth()->user()->name;
        $this->workspaceName = WorkspaceContext::current()->name;
    }

    public function saveProfile(): void
    {
        $data = $this->validate(['profileName' => ['required', 'string', 'max:100']]);
        auth()->user()->update(['name' => $data['profileName']]);
        session()->flash('success', '个人信息已保存。');
    }

    public function saveWorkspace(): void
    {
        abort_unless(WorkspaceContext::canManageMembers(), 403);
        $data = $this->validate(['workspaceName' => ['required', 'string', 'max:100']]);
        WorkspaceContext::current()->update(['name' => $data['workspaceName']]);
        session()->flash('success', '工作区已更新。');
    }

    public function updatePassword(): void
    {
        $this->validate([
            'oldPassword' => ['required', 'string'],
            'password' => ['required', 'string', 'min:12', 'confirmed'],
        ]);
        if (! Hash::check($this->oldPassword, auth()->user()->password)) {
            $this->addError('oldPassword', '当前密码不正确。');
            return;
        }
        auth()->user()->update(['password' => Hash::make($this->password)]);
        $this->reset('oldPassword', 'password', 'password_confirmation');
        session()->flash('success', '密码已更新。');
    }

    public function render()
    {
        return view('livewire.settings-page', ['canManage' => WorkspaceContext::canManageMembers()]);
    }
}
