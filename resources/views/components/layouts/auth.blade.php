<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net"><link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
    @livewireStyles
</head>
<body class="min-h-screen bg-[#f7f8fc] text-zinc-800 antialiased dark:bg-zinc-950 dark:text-zinc-100">
    <div class="grid min-h-screen lg:grid-cols-2">
        <div class="flex flex-col px-6 py-7 sm:px-12 lg:px-20">
            <a href="{{ route('home') }}" class="flex items-center gap-3 text-sm font-bold"><span class="flex size-9 items-center justify-center rounded-xl bg-indigo-600 font-black text-white">OF</span> OrbitFlow</a>
            <main class="mx-auto flex w-full max-w-[410px] flex-1 flex-col justify-center py-12">{{ $slot }}</main>
            <p class="text-xs text-zinc-400">© {{ date('Y') }} OrbitFlow. 基于 Laravel、Livewire 和 Flux UI。</p>
        </div>
        <aside class="relative hidden overflow-hidden bg-[#151b41] p-16 text-white lg:flex lg:flex-col lg:justify-between">
            <div aria-hidden="true" class="absolute -right-44 top-20 size-[500px] rounded-full bg-indigo-500/30 blur-3xl"></div>
            <div aria-hidden="true" class="absolute -left-32 bottom-0 size-[400px] rounded-full bg-violet-400/25 blur-3xl"></div>
            <div class="relative text-sm font-semibold text-indigo-200">A BETTER WAY TO WORK</div>
            <div class="relative space-y-8"><p class="max-w-xl text-5xl font-semibold leading-tight tracking-tight">让想法有序落地，<br>让团队保持同步。</p><p class="max-w-md text-base leading-relaxed text-indigo-200">从灵感、任务到项目交付，所有协作都在一个清晰、专注的工作区中进行。</p><div class="grid max-w-md grid-cols-3 gap-3"><div class="rounded-2xl border border-white/15 bg-white/10 p-5"><div class="text-2xl font-semibold">01</div><div class="mt-3 text-xs text-indigo-200">创建项目</div></div><div class="rounded-2xl border border-white/15 bg-white/10 p-5"><div class="text-2xl font-semibold">02</div><div class="mt-3 text-xs text-indigo-200">分配任务</div></div><div class="rounded-2xl border border-white/15 bg-white/10 p-5"><div class="text-2xl font-semibold">03</div><div class="mt-3 text-xs text-indigo-200">追踪进度</div></div></div></div>
            <p class="relative text-xs text-indigo-300">Designed with Flux UI · Built for focus</p>
        </aside>
    </div>
    @livewireScripts
    @fluxScripts
</body>
</html>
