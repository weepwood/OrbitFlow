<?php
namespace App\Livewire;

use App\Models\Project;
use App\Support\WorkspaceContext;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class ProjectPage extends Component
{
    public int $projectId;
    public string $taskTitle = '';
    public string $taskDescription = '';
    public string $priority = 'medium';
    public string $taskDueDate = '';
    public string $assigneeId = '';
    public bool $showTaskModal = false;

    public function mount(Project $project): void
    {
        abort_unless($project->workspace_id === WorkspaceContext::current()->id, 404);
        $this->projectId = $project->id;
    }

    private function project(): Project
    {
        return WorkspaceContext::current()->projects()->findOrFail($this->projectId);
    }

    public function addTask(): void
    {
        $data = $this->validate([
            'taskTitle' => ['required', 'string', 'max:180'],
            'taskDescription' => ['nullable', 'string', 'max:2000'],
            'priority' => ['required', Rule::in(['low', 'medium', 'high'])],
            'taskDueDate' => ['nullable', 'date_format:Y-m-d'],
            'assigneeId' => ['nullable', 'integer'],
        ]);
        $project = $this->project();
        if ($this->assigneeId !== '') {
            abort_unless(WorkspaceContext::current()->users()->whereKey((int) $this->assigneeId)->exists(), 422);
        }
        $project->tasks()->create([
            'title' => $data['taskTitle'], 'description' => $data['taskDescription'] ?: null,
            'priority' => $data['priority'], 'due_date' => $data['taskDueDate'] ?: null,
            'assignee_id' => $this->assigneeId !== '' ? (int) $this->assigneeId : null,
        ]);
        $this->reset('taskTitle', 'taskDescription', 'taskDueDate', 'assigneeId');
        $this->priority = 'medium';
        $this->showTaskModal = false;
        session()->flash('success', '任务已添加。');
    }

    public function updateTaskStatus(int $taskId, string $status): void
    {
        abort_unless(in_array($status, ['todo', 'in_progress', 'done'], true), 422);
        $this->project()->tasks()->findOrFail($taskId)->update(['status' => $status]);
    }

    public function deleteTask(int $taskId): void
    {
        $this->project()->tasks()->findOrFail($taskId)->delete();
        session()->flash('success', '任务已删除。');
    }

    public function render()
    {
        $project = $this->project()->load(['creator']);
        return view('livewire.project-page', [
            'project' => $project,
            'tasks' => $project->tasks()->with('assignee')->orderByRaw("CASE status WHEN 'in_progress' THEN 0 WHEN 'todo' THEN 1 ELSE 2 END")->latest()->get(),
            'members' => WorkspaceContext::current()->users()->orderBy('name')->get(),
        ]);
    }
}
