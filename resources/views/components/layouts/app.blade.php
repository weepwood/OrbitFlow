@php
    $workspace = \App\Support\WorkspaceContext::current();
    $workspaces = auth()->user()->workspaces()->orderBy('name')->get();
    $currentPage = match (true) {
        request()->routeIs('dashboard') => '工作概览',
        request()->routeIs('projects.show') => '项目详情',
        request()->routeIs('projects.*') => '项目管理',
        request()->routeIs('team.*') => '团队成员',
        request()->routeIs('billing.*') => '订阅计划',
        request()->routeIs('settings.*') => '账户设置',
        default => '工作台',
    };
@endphp
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#f7f8fb">
    <title>{{ $currentPage }} · {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
    @livewireStyles
</head>
<body class="min-h-dvh bg-[#f7f8fb] antialiased dark:bg-[#101117]">
    <flux:sidebar sticky collapsible="mobile" class="orbit-sidebar !border-r !border-zinc-200/80 dark:!border-zinc-800">
        <flux:sidebar.header>
            <a href="{{ route('dashboard') }}" wire:navigate aria-label="OrbitFlow 工作台首页" class="flex items-center gap-3 rounded-xl p-1.5">
                <span class="orbit-mark" aria-hidden="true">OF</span>
                <span class="min-w-0">
                    <span class="block text-[15px] font-bold tracking-[-.035em] text-zinc-900 dark:text-zinc-50">OrbitFlow</span>
                    <span class="mt-0.5 block text-[9px] font-semibold uppercase tracking-[.18em] text-zinc-400">Team workspace</span>
                </span>
            </a>
            <flux:sidebar.collapse class="lg:hidden" />
        </flux:sidebar.header>

        <div class="orbit-workspace mx-3 mt-6">
            <div class="mb-2 flex items-center gap-2">
                <span class="size-1.5 rounded-full bg-emerald-500" aria-hidden="true"></span>
                <span class="text-[10px] font-semibold uppercase tracking-[.12em] text-zinc-500 dark:text-zinc-400">当前工作区</span>
            </div>
            <form action="{{ route('workspaces.switch') }}" method="POST">
                @csrf
                <label for="workspace-switcher" class="sr-only">切换工作区</label>
                <select id="workspace-switcher" name="workspace_id" onchange="this.form.submit()" class="w-full cursor-pointer rounded-md border-0 bg-transparent py-1 text-sm font-semibold outline-none focus-visible:outline-2 focus-visible:outline-offset-2">
                    @foreach($workspaces as $item)
                        <option value="{{ $item->id }}" @selected($item->id === $workspace->id)>{{ $item->name }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="orbit-nav-label px-5 pb-2 pt-7 text-[10px] font-bold uppercase text-zinc-400">工作空间</div>
        <flux:sidebar.nav>
            <flux:sidebar.item icon="squares-2x2" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>工作概览</flux:sidebar.item>
            <flux:sidebar.item icon="folder-open" :href="route('projects.index')" :current="request()->routeIs('projects.*')" wire:navigate>项目管理</flux:sidebar.item>
            <flux:sidebar.item icon="users" :href="route('team.index')" :current="request()->routeIs('team.*')" wire:navigate>团队成员</flux:sidebar.item>
        </flux:sidebar.nav>
        <div class="orbit-nav-label px-5 pb-2 pt-7 text-[10px] font-bold uppercase text-zinc-400">管理与偏好</div>
        <flux:sidebar.nav>
            <flux:sidebar.item icon="credit-card" :href="route('billing.index')" :current="request()->routeIs('billing.*')" wire:navigate>订阅计划</flux:sidebar.item>
            <flux:sidebar.item icon="cog-6-tooth" :href="route('settings.index')" :current="request()->routeIs('settings.*')" wire:navigate>账户设置</flux:sidebar.item>
        </flux:sidebar.nav>

        <flux:sidebar.spacer />
        <div class="mx-3 mb-3 rounded-xl border border-zinc-200/70 bg-white/70 p-3 dark:border-zinc-800 dark:bg-zinc-900/70">
            <div class="flex items-center gap-2.5">
                <div class="orbit-avatar" aria-hidden="true">{{ mb_substr(auth()->user()->name, 0, 1) }}</div>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-xs font-semibold text-zinc-900 dark:text-white">{{ auth()->user()->name }}</p>
                    <p class="mt-0.5 truncate text-[11px] text-zinc-500">{{ auth()->user()->email }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <flux:button type="submit" size="sm" variant="ghost" icon="arrow-right-start-on-rectangle" aria-label="退出登录" />
                </form>
            </div>
        </div>
        <p class="mb-5 px-5 text-[10px] tracking-wide text-zinc-400">OrbitFlow <span aria-hidden="true">·</span> Built with Flux UI</p>
    </flux:sidebar>

    <flux:header class="border-b border-zinc-200/80 bg-white lg:hidden dark:border-zinc-800 dark:bg-zinc-900">
        <flux:sidebar.toggle icon="bars-2" inset="left" />
        <flux:spacer />
        <span class="text-sm font-semibold tracking-tight">OrbitFlow</span>
    </flux:header>

    <flux:main class="orbit-shell-main !px-4 !py-6 sm:!px-7 md:!px-10 md:!py-8">
        <div class="mx-auto max-w-[1280px]">
            <div class="orbit-breadcrumb mb-7">
                <flux:icon.home class="size-3.5" />
                <span class="max-w-32 truncate">{{ $workspace->name }}</span>
                <span class="orbit-breadcrumb-separator" aria-hidden="true"></span>
                <span class="font-medium text-zinc-700 dark:text-zinc-200" aria-current="page">{{ $currentPage }}</span>
            </div>
            @if (session('success'))
                <div role="status" class="mb-6 flex items-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-900 dark:bg-emerald-950 dark:text-emerald-200">
                    <flux:icon.check-circle class="size-5 shrink-0" /> {{ session('success') }}
                </div>
            @endif
            {{ $slot }}
        </div>
    </flux:main>
    @livewireScripts
    @fluxScripts
</body>
</html>