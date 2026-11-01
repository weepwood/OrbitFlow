<?php
namespace App\Livewire;

use App\Models\Project;
use App\Support\WorkspaceContext;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
class ProjectsPage extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filter = 'all';
    public bool $showModal = false;
    public ?int $editId = null;
    public string $name = '';
    public string $description = '';
    public string $status = 'planning';
    public string $dueDate = '';

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatedFilter(): void { $this->resetPage(); }

    public function openCreate(): void
    {
        $this->reset('editId', 'name', 'description', 'dueDate');
        $this->status = 'planning';
        $this->resetValidation();
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $project = WorkspaceContext::current()->projects()->findOrFail($id);
        $this->editId = $project->id;
        $this->name = $project->name;
        $this->description = $project->description ?? '';
        $this->status = $project->status;
        $this->dueDate = $project->due_date?->format('Y-m-d') ?? '';
        $this->resetValidation();
        $this->showModal = true;
    }

    public function save(): void
    {
        $data = $this->validate([
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:2000'],
            'status' => ['required', Rule::in(['planning', 'active', 'on_hold', 'completed'])],
            'dueDate' => ['nullable', 'date_format:Y-m-d'],
        ]);
        $workspace = WorkspaceContext::current();
        $values = [
            'name' => $data['name'], 'description' => $data['description'] ?: null,
            'status' => $data['status'], 'due_date' => $data['dueDate'] ?: null,
        ];
        if ($this->editId) {
            $workspace->projects()->findOrFail($this->editId)->update($values);
            session()->flash('success', '项目已更新。');
        } else {
            $workspace->projects()->create($values + ['creator_id' => auth()->id()]);
            session()->flash('success', '项目已创建。');
        }
        $this->showModal = false;
        $this->reset('editId', 'name', 'description', 'dueDate');
    }

    public function delete(int $id): void
    {
        abort_unless(WorkspaceContext::canManageMembers(), 403);
        WorkspaceContext::current()->projects()->findOrFail($id)->delete();
        session()->flash('success', '项目及其任务已删除。');
    }

    public function render()
    {
        $query = WorkspaceContext::current()->projects()->withCount('tasks')->latest();
        if ($this->search !== '') $query->where('name', 'like', '%'.addcslashes($this->search, '%_\\').'%');
        if ($this->filter !== 'all') {
            abort_unless(in_array($this->filter, ['planning','active','on_hold','completed'], true), 422);
            $query->where('status', $this->filter);
        }
        return view('livewire.projects-page', ['projects' => $query->paginate(10)]);
    }
}
