<?php
namespace App\Livewire;

use App\Models\Task;
use App\Support\WorkspaceContext;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class DashboardPage extends Component
{
    public function render()
    {
        $workspace = WorkspaceContext::current();
        $projects = $workspace->projects();
        $projectIds = $workspace->projects()->select('id');
        $tasks = Task::query()->whereIn('project_id', $projectIds);
        return view('livewire.dashboard-page', [
            'workspace' => $workspace,
            'projectCount' => (clone $projects)->count(),
            'activeCount' => (clone $projects)->where('status', 'active')->count(),
            'taskCount' => (clone $tasks)->count(),
            'completedTaskCount' => (clone $tasks)->where('status', 'done')->count(),
            'recentProjects' => $workspace->projects()->withCount(['tasks', 'tasks as done_tasks_count' => fn ($q) => $q->where('status', 'done')])->latest()->limit(5)->get(),
            'upcomingTasks' => Task::query()->with('project')->whereIn('project_id', $workspace->projects()->select('id'))
                ->where('status', '!=', 'done')->whereNotNull('due_date')->orderBy('due_date')->limit(5)->get(),
        ]);
    }
}
