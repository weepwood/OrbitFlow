@php
    $completionRate = $taskCount ? (int) round($completedTaskCount * 100 / $taskCount) : 0;
@endphp
<div class="space-y-7 pb-8">
    <header class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="section-kicker flex items-center gap-2"><span class="dot bg-indigo-500" aria-hidden="true"></span> WORKSPACE OVERVIEW</p>
            <h1 class="orbit-page-title mt-3 text-[29px] font-semibold leading-tight text-zinc-950 sm:text-[36px] dark:text-white">欢迎回来，{{ auth()->user()->name }}</h1>
            <p class="mt-2 text-sm leading-6 text-zinc-500 dark:text-zinc-400">{{ $workspace->name }} 的项目与任务进展，一目了然。</p>
        </div>
        <flux:button :href="route('projects.index')" variant="primary" icon="arrow-up-right" wire:navigate>进入项目管理</flux:button>
    </header>

    <section aria-label="工作区关键指标" class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
        <div class="surface orbit-stat-card p-5 sm:p-6">
            <div class="flex items-start justify-between gap-3">
                <span class="text-[13px] font-medium text-zinc-500 dark:text-zinc-400">全部项目</span>
                <span class="orbit-icon-tile bg-indigo-50 text-indigo-600 dark:bg-indigo-950 dark:text-indigo-300"><flux:icon.folder-open class="size-5" /></span>
            </div>
            <div class="stat-value">{{ $projectCount }}</div>
            <p class="mt-3 text-xs text-zinc-400">当前工作区项目</p>
        </div>
        <div class="surface orbit-stat-card p-5 sm:p-6">
            <div class="flex items-start justify-between gap-3">
                <span class="text-[13px] font-medium text-zinc-500 dark:text-zinc-400">进行中</span>
                <span class="orbit-icon-tile bg-sky-50 text-sky-600 dark:bg-sky-950 dark:text-sky-300"><flux:icon.arrow-trending-up class="size-5" /></span>
            </div>
            <div class="stat-value">{{ $activeCount }}</div>
            <p class="mt-3 text-xs text-zinc-400">正在推进的项目</p>
        </div>
        <div class="surface orbit-stat-card p-5 sm:p-6">
            <div class="flex items-start justify-between gap-3">
                <span class="text-[13px] font-medium text-zinc-500 dark:text-zinc-400">全部任务</span>
                <span class="orbit-icon-tile bg-violet-50 text-violet-600 dark:bg-violet-950 dark:text-violet-300"><flux:icon.clipboard-document-check class="size-5" /></span>
            </div>
            <div class="stat-value">{{ $taskCount }}</div>
            <p class="mt-3 text-xs text-zinc-400">跨项目任务总数</p>
        </div>
        <div class="surface orbit-stat-card p-5 sm:p-6">
            <div class="flex items-start justify-between gap-3">
                <span class="text-[13px] font-medium text-zinc-500 dark:text-zinc-400">已完成任务</span>
                <span class="orbit-icon-tile bg-emerald-50 text-emerald-600 dark:bg-emerald-950 dark:text-emerald-300"><flux:icon.check-circle class="size-5" /></span>
            </div>
            <div class="stat-value">{{ $completedTaskCount }}</div>
            <p class="mt-3 text-xs text-zinc-400">整体完成率 {{ $completionRate }}%</p>
        </div>
    </section>

    <section aria-label="总体完成情况" class="surface flex flex-col gap-5 px-5 py-5 sm:flex-row sm:items-center sm:gap-8 sm:px-7">
        <div class="flex min-w-0 items-center gap-3 sm:w-60 sm:shrink-0">
            <span class="orbit-icon-tile bg-indigo-50 text-indigo-600 dark:bg-indigo-950 dark:text-indigo-300"><flux:icon.chart-bar-square class="size-5" /></span>
            <div>
                <h2 class="text-sm font-semibold text-zinc-900 dark:text-white">整体任务进度</h2>
                <p class="mt-0.5 text-[11px] text-zinc-400">工作区全部任务</p>
            </div>
        </div>
        <div class="flex flex-1 items-center gap-4">
            <div class="orbit-progress-track flex-1" role="progressbar" aria-label="任务完成率" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $completionRate }}">
                <div class="orbit-progress-fill" style="width: {{ $completionRate }}%"></div>
            </div>
            <strong class="w-12 text-right text-sm font-semibold tabular-nums text-zinc-900 dark:text-white">{{ $completionRate }}%</strong>
        </div>
    </section>

    <div class="grid gap-6 xl:grid-cols-[minmax(0,1.55fr)_minmax(320px,1fr)]">
        <section class="surface min-w-0 overflow-hidden">
            <header class="orbit-panel-heading">
                <div>
                    <h2 class="text-[15px] font-semibold tracking-tight text-zinc-900 dark:text-white">最近项目</h2>
                    <p class="mt-1 text-xs text-zinc-400">按创建时间查看最新工作</p>
                </div>
                <flux:button :href="route('projects.index')" size="sm" variant="ghost" icon:trailing="arrow-up-right" wire:navigate>查看全部</flux:button>
            </header>
            <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
                @forelse($recentProjects as $project)
                    @php
                        $projectRate = $project->tasks_count ? (int) round($project->done_tasks_count * 100 / $project->tasks_count) : 0;
                    @endphp
                    <a href="{{ route('projects.show', $project) }}" wire:navigate class="orbit-project-row flex items-center gap-3 px-4 py-4 sm:gap-4 sm:px-6" aria-label="查看项目 {{ $project->name }}">
                        <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-sm font-bold text-indigo-600 dark:bg-indigo-950 dark:text-indigo-300" aria-hidden="true">{{ mb_substr($project->name, 0, 1) }}</span>
                        <span class="min-w-0 flex-1">
                            <span class="block truncate text-[13px] font-semibold text-zinc-900 dark:text-white">{{ $project->name }}</span>
                            <span class="mt-1 block text-[11px] text-zinc-400">{{ $project->done_tasks_count }} / {{ $project->tasks_count }} 个任务已完成</span>
                        </span>
                        <span class="hidden w-24 shrink-0 sm:block">
                            <span class="mb-1.5 block text-right text-[11px] font-semibold tabular-nums text-zinc-500">{{ $projectRate }}%</span>
                            <span class="orbit-progress-track block" role="progressbar" aria-label="{{ $project->name }} 任务完成率" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $projectRate }}"><span class="orbit-progress-fill block" style="width: {{ $projectRate }}%"></span></span>
                        </span>
                        <flux:badge size="sm" :color="match($project->status) { 'active' => 'blue', 'completed' => 'green', 'on_hold' => 'amber', default => 'zinc' }">{{ match($project->status) { 'active' => '进行中', 'completed' => '已完成', 'on_hold' => '已暂停', default => '规划中' } }}</flux:badge>
                        <flux:icon.chevron-right class="hidden size-4 shrink-0 text-zinc-400 sm:block" />
                    </a>
                @empty
                    <div class="px-6 py-14 text-center">
                        <span class="mx-auto flex size-12 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-500 dark:bg-indigo-950"><flux:icon.folder-plus class="size-6" /></span>
                        <p class="mt-4 text-sm font-medium text-zinc-700 dark:text-zinc-200">还没有项目</p>
                        <p class="mt-1 text-xs text-zinc-400">创建第一个项目，开始记录团队的工作。</p>
                        <flux:button :href="route('projects.index')" class="mt-5" size="sm" wire:navigate>新建项目</flux:button>
                    </div>
                @endforelse
            </div>
        </section>

        <div class="min-w-0 space-y-6">
            <section class="surface overflow-hidden">
                <header class="orbit-panel-heading">
                    <div>
                        <h2 class="text-[15px] font-semibold tracking-tight text-zinc-900 dark:text-white">近期截止任务</h2>
                        <p class="mt-1 text-xs text-zinc-400">优先关注临近截止的事项</p>
                    </div>
                    <span class="orbit-tag">{{ $upcomingTasks->count() }} 项</span>
                </header>
                <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    @forelse($upcomingTasks as $task)
                        <a href="{{ route('projects.show', $task->project) }}" wire:navigate class="orbit-project-row flex items-center gap-3 px-5 py-4 sm:px-6">
                            <span class="flex size-8 shrink-0 items-center justify-center rounded-lg bg-zinc-100 text-zinc-500 dark:bg-zinc-800 dark:text-zinc-400"><flux:icon.calendar-days class="size-4" /></span>
                            <span class="min-w-0 flex-1">
                                <span class="block truncate text-[13px] font-medium text-zinc-900 dark:text-white">{{ $task->title }}</span>
                                <span class="mt-1 block truncate text-[11px] text-zinc-400">{{ $task->project->name }}</span>
                            </span>
                            <time datetime="{{ $task->due_date->toDateString() }}" class="shrink-0 rounded-lg px-2 py-1.5 text-[11px] font-semibold tabular-nums {{ $task->due_date->isPast() ? 'bg-red-50 text-red-600 dark:bg-red-950 dark:text-red-300' : 'bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300' }}">{{ $task->due_date->format('m/d') }}</time>
                        </a>
                    @empty
                        <div class="px-6 py-12 text-center">
                            <flux:icon.check-circle class="mx-auto size-7 text-emerald-500" />
                            <p class="mt-3 text-sm text-zinc-500">目前没有待截止的任务</p>
                        </div>
                    @endforelse
                </div>
            </section>
            <section class="orbit-focus-panel p-6 sm:p-7">
                <div class="relative z-10">
                    <span class="text-[10px] font-semibold uppercase tracking-[.2em] text-violet-200">STAY IN FLOW</span>
                    <h2 class="mt-4 max-w-[250px] text-[21px] font-semibold leading-[1.4] tracking-tight">清晰的下一步，<br>让每个项目稳步前进。</h2>
                    <p class="mt-3 max-w-[290px] text-xs leading-6 text-zinc-300">从计划到完成，让团队始终专注最重要的事情。</p>
                    <a href="{{ route('projects.index') }}" wire:navigate class="mt-5 inline-flex items-center gap-2 rounded-lg bg-white px-3.5 py-2 text-xs font-semibold text-[#24243c] transition hover:bg-violet-100">查看所有项目 <flux:icon.arrow-up-right class="size-3.5" /></a>
                </div>
            </section>
        </div>
    </div>
</div>