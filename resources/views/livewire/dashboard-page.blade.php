<div class="space-y-9">
    <div class="flex flex-col justify-between gap-5 sm:flex-row sm:items-end">
        <div><div class="section-kicker">WORKSPACE OVERVIEW</div><h1 class="mt-2 text-3xl font-semibold tracking-tight text-zinc-950 dark:text-white">你好，{{ auth()->user()->name }} <span class="text-2xl">✳</span></h1><p class="mt-2 text-sm text-zinc-500">欢迎回到 {{ $workspace->name }}。这里是你们的工作进展。</p></div>
        <flux:button :href="route('projects.index')" variant="primary" icon="plus" wire:navigate>管理项目</flux:button>
    </div>
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="surface p-6"><div class="flex items-center justify-between"><span class="text-sm text-zinc-500">全部项目</span><span class="flex size-9 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-950"><flux:icon.folder-open class="size-5" /></span></div><div class="stat-value">{{ $projectCount }}</div><p class="mt-2 text-xs text-zinc-400">工作区项目总数</p></div>
        <div class="surface p-6"><div class="flex items-center justify-between"><span class="text-sm text-zinc-500">进行中项目</span><span class="flex size-9 items-center justify-center rounded-xl bg-sky-50 text-sky-600 dark:bg-sky-950"><flux:icon.arrow-trending-up class="size-5" /></span></div><div class="stat-value">{{ $activeCount }}</div><p class="mt-2 text-xs text-zinc-400">当前执行阶段</p></div>
        <div class="surface p-6"><div class="flex items-center justify-between"><span class="text-sm text-zinc-500">全部任务</span><span class="flex size-9 items-center justify-center rounded-xl bg-violet-50 text-violet-600 dark:bg-violet-950"><flux:icon.clipboard-document-check class="size-5" /></span></div><div class="stat-value">{{ $taskCount }}</div><p class="mt-2 text-xs text-zinc-400">跨项目任务总数</p></div>
        <div class="surface p-6"><div class="flex items-center justify-between"><span class="text-sm text-zinc-500">已完成任务</span><span class="flex size-9 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-950"><flux:icon.check-circle class="size-5" /></span></div><div class="stat-value">{{ $completedTaskCount }}</div><p class="mt-2 text-xs text-zinc-400">完成率 {{ $taskCount ? round($completedTaskCount * 100 / $taskCount) : 0 }}%</p></div>
    </div>
    <div class="grid gap-6 xl:grid-cols-[1.55fr_1fr]">
        <section class="surface overflow-hidden">
            <header class="flex items-center justify-between gap-3 border-b border-zinc-100 px-6 py-5 dark:border-zinc-800"><div><h2 class="font-semibold text-zinc-900 dark:text-white">最近项目</h2><p class="mt-1 text-xs text-zinc-400">快速查看当前团队的工作</p></div><flux:button :href="route('projects.index')" size="sm" variant="ghost" icon:trailing="arrow-up-right" wire:navigate>全部项目</flux:button></header>
            @forelse($recentProjects as $project)
                <a href="{{ route('projects.show', $project) }}" wire:navigate class="flex items-center gap-4 border-b border-zinc-100 px-6 py-4 transition hover:bg-zinc-50 last:border-0 dark:border-zinc-800 dark:hover:bg-zinc-800/40">
                    <div class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-indigo-50 font-semibold text-indigo-600 dark:bg-indigo-950">{{ mb_substr($project->name, 0, 1) }}</div>
                    <div class="min-w-0 flex-1"><div class="truncate text-sm font-semibold text-zinc-900 dark:text-white">{{ $project->name }}</div><p class="mt-1 text-xs text-zinc-400">{{ $project->tasks_count }} 个任务 · 完成 {{ $project->done_tasks_count }} 个</p></div>
                    <flux:badge size="sm" :color="match($project->status) { 'active' => 'blue', 'completed' => 'green', 'on_hold' => 'amber', default => 'zinc' }">{{ match($project->status) { 'active' => '进行中', 'completed' => '已完成', 'on_hold' => '已暂停', default => '规划中' } }}</flux:badge>
                    <flux:icon.chevron-right class="size-4 text-zinc-400" />
                </a>
            @empty
                <div class="px-6 py-14 text-center"><flux:icon.folder-plus class="mx-auto size-9 text-zinc-300" /><p class="mt-4 text-sm text-zinc-500">还没有项目，创建第一个项目开始吧。</p><flux:button :href="route('projects.index')" class="mt-4" size="sm" wire:navigate>前往项目管理</flux:button></div>
            @endforelse
        </section>
        <div class="space-y-6">
            <section class="surface overflow-hidden"><header class="border-b border-zinc-100 px-6 py-5 dark:border-zinc-800"><h2 class="font-semibold">待办截止日期</h2><p class="mt-1 text-xs text-zinc-400">近期需要关注的任务</p></header>
                <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
                @forelse($upcomingTasks as $task)
                    <a class="block px-6 py-4 transition hover:bg-zinc-50 dark:hover:bg-zinc-800/40" href="{{ route('projects.show', $task->project) }}" wire:navigate><div class="flex justify-between gap-4"><div class="min-w-0"><p class="truncate text-sm font-medium">{{ $task->title }}</p><p class="mt-1 truncate text-xs text-zinc-400">{{ $task->project->name }}</p></div><span class="shrink-0 text-xs font-medium {{ $task->due_date->isPast() ? 'text-red-600' : 'text-zinc-400' }}">{{ $task->due_date->format('m/d') }}</span></div></a>
                @empty<div class="px-6 py-10 text-center text-sm text-zinc-400">目前没有带截止日期的待办任务。</div>@endforelse
                </div>
            </section>
            <section class="rounded-2xl bg-gradient-to-br from-[#273e94] to-[#6d5df0] p-7 text-white"><p class="text-[11px] font-semibold tracking-[.18em] text-indigo-200">FOCUS ON WHAT MATTERS</p><h3 class="mt-4 max-w-xs text-xl font-semibold leading-8">把团队目标转化为清晰、可执行的下一步。</h3><flux:button :href="route('projects.index')" variant="filled" class="mt-6" size="sm" wire:navigate>查看项目 <flux:icon.arrow-right class="ml-1 size-4" /></flux:button></section>
        </div>
    </div>
</div>
