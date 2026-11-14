<div class="space-y-7 pb-8">
    <header class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="section-kicker">PROJECT WORKSPACE</p>
            <h1 class="orbit-page-title mt-3 text-[29px] font-semibold leading-tight text-zinc-950 sm:text-[36px] dark:text-white">项目管理</h1>
            <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">将想法整理为项目，在同一个地方跟踪交付进度。</p>
        </div>
        <flux:button variant="primary" icon="plus" wire:click="openCreate">新建项目</flux:button>
    </header>

    <section class="surface overflow-hidden" aria-label="项目列表">
        <div class="flex flex-col gap-4 border-b border-zinc-100 px-4 py-5 sm:px-6 lg:flex-row lg:items-center lg:justify-between dark:border-zinc-800">
            <div class="flex min-w-0 items-center gap-3">
                <span class="orbit-icon-tile shrink-0 bg-indigo-50 text-indigo-600 dark:bg-indigo-950 dark:text-indigo-300"><flux:icon.rectangle-stack class="size-5" /></span>
                <div class="min-w-0">
                    <h2 class="text-sm font-semibold text-zinc-900 dark:text-white">所有项目</h2>
                    <p class="mt-0.5 text-[11px] text-zinc-400">当前筛选下共有 {{ $projects->total() }} 个项目</p>
                </div>
            </div>
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <div class="w-full sm:w-64">
                    <flux:input wire:model.live.debounce.300ms="search" placeholder="搜索项目名称…" icon="magnifying-glass" size="sm" aria-label="搜索项目" />
                </div>
                <div class="w-full sm:w-40">
                    <flux:select wire:model.live="filter" size="sm" aria-label="按项目状态筛选">
                        <flux:select.option value="all">全部状态</flux:select.option>
                        <flux:select.option value="planning">规划中</flux:select.option>
                        <flux:select.option value="active">进行中</flux:select.option>
                        <flux:select.option value="on_hold">已暂停</flux:select.option>
                        <flux:select.option value="completed">已完成</flux:select.option>
                    </flux:select>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <flux:table :paginate="$projects">
                <flux:table.columns>
                    <flux:table.column>项目</flux:table.column>
                    <flux:table.column>状态</flux:table.column>
                    <flux:table.column>任务进度</flux:table.column>
                    <flux:table.column>截止日期</flux:table.column>
                    <flux:table.column>操作</flux:table.column>
                </flux:table.columns>
                <flux:table.rows>
                    @forelse($projects as $project)
                        @php $projectRate = $project->tasks_count ? (int) round($project->done_tasks_count * 100 / $project->tasks_count) : 0; @endphp
                        <flux:table.row :key="$project->id">
                            <flux:table.cell>
                                <a href="{{ route('projects.show', $project) }}" wire:navigate class="group flex min-w-[200px] items-center gap-3 py-1">
                                    <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-sm font-bold text-indigo-600 dark:bg-indigo-950 dark:text-indigo-300" aria-hidden="true">{{ mb_substr($project->name, 0, 1) }}</span>
                                    <span class="min-w-0">
                                        <span class="block max-w-[280px] truncate text-[13px] font-semibold text-zinc-900 group-hover:text-indigo-600 dark:text-white dark:group-hover:text-indigo-300">{{ $project->name }}</span>
                                        <span class="mt-1 block max-w-[280px] truncate text-[11px] text-zinc-400">{{ $project->description ?: '暂无项目描述' }}</span>
                                    </span>
                                </a>
                            </flux:table.cell>
                            <flux:table.cell>
                                <flux:badge size="sm" :color="match($project->status) { 'active' => 'blue', 'completed' => 'green', 'on_hold' => 'amber', default => 'zinc' }">{{ match($project->status) { 'active' => '进行中', 'completed' => '已完成', 'on_hold' => '已暂停', default => '规划中' } }}</flux:badge>
                            </flux:table.cell>
                            <flux:table.cell>
                                <div class="min-w-[110px] space-y-2" role="group" aria-label="{{ $project->name }} 任务完成进度">
                                    <div class="flex justify-between gap-2 text-[11px] tabular-nums"><span class="text-zinc-500">{{ $project->done_tasks_count }}/{{ $project->tasks_count }} 已完成</span><span class="font-semibold text-zinc-700 dark:text-zinc-300">{{ $projectRate }}%</span></div>
                                    <div class="orbit-progress-track" role="progressbar" aria-valuenow="{{ $projectRate }}" aria-valuemin="0" aria-valuemax="100" aria-label="任务完成率">
                                        <div class="orbit-progress-fill" style="width: {{ $projectRate }}%"></div>
                                    </div>
                                </div>
                            </flux:table.cell>
                            <flux:table.cell>
                                <span class="whitespace-nowrap text-xs text-zinc-600 dark:text-zinc-300">{{ $project->due_date?->format('Y-m-d') ?? '未设置' }}</span>
                            </flux:table.cell>
                            <flux:table.cell>
                                <div class="flex items-center gap-1">
                                    <flux:button size="sm" variant="ghost" icon="pencil-square" aria-label="编辑项目 {{ $project->name }}" wire:click="openEdit({{ $project->id }})" />
                                    <flux:button size="sm" variant="ghost" icon="arrow-up-right" aria-label="打开项目 {{ $project->name }}" :href="route('projects.show', $project)" wire:navigate />
                                    @if(\App\Support\WorkspaceContext::canManageMembers())
                                        <flux:button size="sm" variant="ghost" color="red" icon="trash" aria-label="删除项目 {{ $project->name }}" wire:click="delete({{ $project->id }})" wire:confirm="确定删除此项目及全部任务？此操作不可撤销。" />
                                    @endif
                                </div>
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="5">
                                <div class="px-4 py-14 text-center">
                                    <span class="mx-auto flex size-12 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-500 dark:bg-indigo-950"><flux:icon.folder-plus class="size-6" /></span>
                                    <p class="mt-4 text-sm font-semibold text-zinc-800 dark:text-zinc-200">没有找到项目</p>
                                    <p class="mt-1 text-xs text-zinc-400">尝试修改筛选条件，或创建一个新项目。</p>
                                    <flux:button class="mt-5" size="sm" variant="primary" icon="plus" wire:click="openCreate">创建项目</flux:button>
                                </div>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </div>
    </section>

    <flux:modal wire:model.self="showModal" class="md:w-[540px]">
        <form wire:submit="save" class="space-y-5">
            <div><flux:heading size="lg">{{ $editId ? '编辑项目' : '创建新项目' }}</flux:heading><flux:text class="mt-2">填写项目基本信息，后续可继续添加任务。</flux:text></div>
            <flux:input label="项目名称" wire:model="name" placeholder="例如：网站改版" required />
            <flux:textarea label="项目描述" wire:model="description" rows="3" placeholder="简要描述目标与范围" />
            <div class="grid gap-4 sm:grid-cols-2">
                <flux:select label="状态" wire:model="status"><flux:select.option value="planning">规划中</flux:select.option><flux:select.option value="active">进行中</flux:select.option><flux:select.option value="on_hold">已暂停</flux:select.option><flux:select.option value="completed">已完成</flux:select.option></flux:select>
                <flux:input type="date" label="截止日期（可选）" wire:model="dueDate" />
            </div>
            <div class="flex justify-end gap-2 pt-2"><flux:button type="button" variant="ghost" wire:click="$set('showModal', false)">取消</flux:button><flux:button type="submit" variant="primary">{{ $editId ? '保存修改' : '创建项目' }}</flux:button></div>
        </form>
    </flux:modal>
</div>