@php
    $workspace = \App\Support\WorkspaceContext::current();
    $workspaces = auth()->user()->workspaces()->orderBy('name')->get();
@endphp
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} · 工作台</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
    @livewireStyles
</head>
<body class="min-h-dvh bg-zinc-50 antialiased dark:bg-zinc-950">
    <flux:sidebar sticky collapsible="mobile" class="border-r border-zinc-200 bg-white dark:border-zinc-800 dark:bg-zinc-950">
        <flux:sidebar.header>
            <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-3 p-1.5">
                <div class="flex size-9 items-center justify-center rounded-xl bg-indigo-600 text-sm font-black tracking-tighter text-white shadow-sm">OF</div>
                <div><div class="text-sm font-bold tracking-tight text-zinc-950 dark:text-zinc-100">OrbitFlow</div><div class="text-[10px] font-medium uppercase tracking-[.16em] text-zinc-400">Workspace OS</div></div>
            </a>
            <flux:sidebar.collapse class="lg:hidden" />
        </flux:sidebar.header>
        <div class="mx-3 mt-5 rounded-xl border border-zinc-200 bg-zinc-50 p-3 dark:border-zinc-800 dark:bg-zinc-900">
            <p class="mb-2 text-[10px] font-semibold uppercase tracking-widest text-zinc-400">当前工作区</p>
            <form action="{{ route('workspaces.switch') }}" method="POST">
                @csrf
                <label class="sr-only" for="workspace-switcher">切换工作区</label>
                <select id="workspace-switcher" name="workspace_id" onchange="this.form.submit()" class="w-full cursor-pointer border-0 bg-transparent text-sm font-semibold text-zinc-800 outline-none dark:text-zinc-100">
                    @foreach($workspaces as $item)
                        <option value="{{ $item->id }}" @selected($item->id === $workspace->id)>{{ $item->name }}</option>
                    @endforeach
                </select>
            </form>
        </div>
        <div class="px-4 pb-2 pt-7 text-[10px] font-bold uppercase tracking-[.15em] text-zinc-400">工作空间</div>
        <flux:sidebar.nav>
            <flux:sidebar.item icon="squares-2x2" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>概览</flux:sidebar.item>
            <flux:sidebar.item icon="folder-open" :href="route('projects.index')" :current="request()->routeIs('projects.*')" wire:navigate>项目管理</flux:sidebar.item>
            <flux:sidebar.item icon="users" :href="route('team.index')" :current="request()->routeIs('team.*')" wire:navigate>团队成员</flux:sidebar.item>
        </flux:sidebar.nav>
        <div class="px-4 pb-2 pt-7 text-[10px] font-bold uppercase tracking-[.15em] text-zinc-400">账户</div>
        <flux:sidebar.nav>
            <flux:sidebar.item icon="credit-card" :href="route('billing.index')" :current="request()->routeIs('billing.*')" wire:navigate>订阅计划</flux:sidebar.item>
            <flux:sidebar.item icon="cog-6-tooth" :href="route('settings.index')" :current="request()->routeIs('settings.*')" wire:navigate>设置</flux:sidebar.item>
        </flux:sidebar.nav>
        <flux:sidebar.spacer />
        <div class="mx-3 mb-4 space-y-3 border-t border-zinc-200 pt-4 dark:border-zinc-800">
            <div class="flex items-center gap-2 px-1">
                <div class="flex size-9 items-center justify-center rounded-full bg-indigo-100 text-xs font-bold text-indigo-700 dark:bg-indigo-900 dark:text-indigo-200">{{ mb_substr(auth()->user()->name, 0, 1) }}</div>
                <div class="min-w-0 flex-1"><div class="truncate text-sm font-semibold text-zinc-800 dark:text-zinc-200">{{ auth()->user()->name }}</div><div class="truncate text-xs text-zinc-400">{{ auth()->user()->email }}</div></div>
                <form method="POST" action="{{ route('logout') }}">@csrf<flux:button type="submit" size="sm" variant="ghost" icon="arrow-right-start-on-rectangle" aria-label="退出登录" /></form>
            </div>
            <p class="px-1 text-[10px] text-zinc-400">OrbitFlow MVP · Flux UI 2</p>
        </div>
    </flux:sidebar>
    <flux:header class="lg:hidden border-b border-zinc-200 bg-white dark:border-zinc-800 dark:bg-zinc-950">
        <flux:sidebar.toggle icon="bars-2" inset="left" /><flux:spacer /><span class="text-sm font-semibold">OrbitFlow</span>
    </flux:header>
    <flux:main class="!px-5 !py-7 md:!px-10 md:!py-10">
        <div class="mx-auto max-w-[1240px]">
            @if (session('success'))
                <div role="status" class="mb-6 flex items-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-900 dark:bg-emerald-950 dark:text-emerald-200"><flux:icon.check-circle class="size-5" /> {{ session('success') }}</div>
            @endif
            {{ $slot }}
        </div>
    </flux:main>
    @livewireScripts
    @fluxScripts
</body>
</html>
