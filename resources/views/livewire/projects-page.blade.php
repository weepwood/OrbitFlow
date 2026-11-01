<div class="space-y-7">
    <header class="flex flex-col justify-between gap-5 sm:flex-row sm:items-end"><div><p class="section-kicker">PROJECTS</p><h1 class="mt-2 text-3xl font-semibold tracking-tight">项目管理</h1><p class="mt-2 text-sm text-zinc-500">统一整理计划、进度与任务。</p></div><flux:button variant="primary" icon="plus" wire:click="openCreate">新建项目</flux:button></header>
    <section class="surface overflow-hidden">
        <div class="flex flex-col gap-3 border-b border-zinc-100 p-5 dark:border-zinc-800 sm:flex-row sm:items-center sm:justify-between"><div class="max-w-sm flex-1"><flux:input wire:model.live.debounce.300ms="search" placeholder="搜索项目名称…" icon="magnifying-glass" size="sm" /></div><div class="w-full sm:w-44"><flux:select wire:model.live="filter" size="sm"><flux:select.option value="all">全部状态</flux:select.option><flux:select.option value="planning">规划中</flux:select.option><flux:select.option value="active">进行中</flux:select.option><flux:select.option value="on_hold">已暂停</flux:select.option><flux:select.option value="completed">已完成</flux:select.option></flux:select></div></div>
        <div class="overflow-x-auto">
            <flux:table :paginate="$projects">
                <flux:table.columns><flux:table.column>项目</flux:table.column><flux:table.column>状态</flux:table.column><flux:table.column>任务数</flux:table.column><flux:table.column>截止日期</flux:table.column><flux:table.column>操作</flux:table.column></flux:table.columns>
                <flux:table.rows>
                    @forelse($projects as $project)
                        <flux:table.row :key="$project->id"><flux:table.cell><a href="{{ route('projects.show', $project) }}" wire:navigate class="flex min-w-[180px] items-center gap-3"><span class="flex size-9 items-center justify-center rounded-lg bg-indigo-50 font-semibold text-indigo-600 dark:bg-indigo-950">{{ mb_substr($project->name, 0, 1) }}</span><span><span class="block font-semibold text-zinc-900 hover:text-indigo-600 dark:text-white">{{ $project->name }}</span><span class="mt-1 block max-w-[270px] truncate text-xs text-zinc-400">{{ $project->description ?: '暂无项目描述' }}</span></span></a></flux:table.cell>
                            <flux:table.cell><flux:badge size="sm" :color="match($project->status) { 'active' => 'blue', 'completed' => 'green', 'on_hold' => 'amber', default => 'zinc' }">{{ match($project->status) { 'active' => '进行中', 'completed' => '已完成', 'on_hold' => '已暂停', default => '规划中' } }}</flux:badge></flux:table.cell>
                            <flux:table.cell>{{ $project->tasks_count }}</flux:table.cell><flux:table.cell>{{ $project->due_date?->format('Y-m-d') ?? '未设置' }}</flux:table.cell>
                            <flux:table.cell><div class="flex gap-2"><flux:button size="sm" variant="ghost" icon="pencil-square" aria-label="编辑项目" wire:click="openEdit({{ $project->id }})" /><flux:button size="sm" variant="ghost" icon="arrow-up-right" aria-label="打开项目" :href="route('projects.show', $project)" wire:navigate />@if(\App\Support\WorkspaceContext::canManageMembers())<flux:button size="sm" variant="ghost" color="red" icon="trash" aria-label="删除项目" wire:click="delete({{ $project->id }})" wire:confirm="确定删除此项目及全部任务？此操作不可撤销。" />@endif</div></flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row><flux:table.cell colspan="5"><div class="py-12 text-center text-sm text-zinc-400">没有找到项目。试试调整筛选，或创建一个新项目。</div></flux:table.cell></flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </div>
    </section>
    <flux:modal wire:model.self="showModal" class="md:w-[540px]">
        <form wire:submit="save" class="space-y-5"><div><flux:heading size="lg">{{ $editId ? '编辑项目' : '创建新项目' }}</flux:heading><flux:text class="mt-2">填写项目基本信息，后续可继续添加任务。</flux:text></div>
            <flux:input label="项目名称" wire:model="name" placeholder="例如：网站改版" required /><flux:textarea label="项目描述" wire:model="description" rows="3" placeholder="简要描述目标与范围" />
            <div class="grid gap-4 sm:grid-cols-2"><flux:select label="状态" wire:model="status"><flux:select.option value="planning">规划中</flux:select.option><flux:select.option value="active">进行中</flux:select.option><flux:select.option value="on_hold">已暂停</flux:select.option><flux:select.option value="completed">已完成</flux:select.option></flux:select><flux:input type="date" label="截止日期（可选）" wire:model="dueDate" /></div>
            <div class="flex justify-end gap-2 pt-2"><flux:button type="button" variant="ghost" wire:click="$set('showModal', false)">取消</flux:button><flux:button type="submit" variant="primary">{{ $editId ? '保存修改' : '创建项目' }}</flux:button></div>
        </form>
    </flux:modal>
</div>
