<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f7f8fb">
    <title>{{ config('app.name') }} · 账户</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
    @livewireStyles
</head>
<body class="min-h-dvh bg-[#f7f8fb] text-zinc-900 antialiased dark:bg-[#101117] dark:text-zinc-100">
    <div class="grid min-h-dvh lg:grid-cols-[1.05fr_.95fr]">
        <div class="flex min-h-dvh flex-col px-6 py-7 sm:px-12 lg:px-16 xl:px-24">
            <a href="{{ route('home') }}" class="flex w-fit items-center gap-3 text-sm font-bold tracking-tight" aria-label="返回 OrbitFlow 首页">
                <span class="orbit-mark" aria-hidden="true">OF</span>
                <span class="text-[17px] tracking-[-.04em]">OrbitFlow</span>
            </a>
            <main class="mx-auto flex w-full max-w-[420px] flex-1 flex-col justify-center py-16">
                {{ $slot }}
            </main>
            <footer class="text-[11px] leading-5 text-zinc-400">© {{ date('Y') }} OrbitFlow · Designed for focused work.</footer>
        </div>
        <aside class="orbit-focus-panel relative hidden !rounded-none p-12 lg:flex lg:flex-col lg:justify-between xl:p-20" aria-label="OrbitFlow 产品介绍">
            <div class="relative z-10 flex items-center gap-2 text-[11px] font-semibold tracking-[.17em] text-violet-200">
                <span class="dot bg-violet-300" aria-hidden="true"></span> YOUR WORK, IN FLOW
            </div>
            <div class="relative z-10 max-w-xl">
                <h2 class="text-[42px] font-semibold leading-[1.2] tracking-[-.05em] xl:text-[55px]">更少的噪音。<br>更清晰的进展。</h2>
                <p class="mt-6 max-w-md text-sm leading-7 text-zinc-300">从项目规划到任务交付，将团队的重要工作集中在一个安静、有序的空间。</p>
                <div class="mt-10 grid max-w-[480px] gap-3">
                    <div class="flex items-center gap-4 rounded-2xl border border-white/10 bg-white/5 px-5 py-4">
                        <span class="grid size-9 place-items-center rounded-xl bg-white/10"><flux:icon.folder-open class="size-5 text-violet-200" /></span>
                        <span class="flex-1 text-sm font-medium">项目规划与进展</span>
                        <flux:icon.check-circle class="size-5 text-emerald-300" />
                    </div>
                    <div class="flex items-center gap-4 rounded-2xl border border-white/10 bg-white/5 px-5 py-4">
                        <span class="grid size-9 place-items-center rounded-xl bg-white/10"><flux:icon.clipboard-document-check class="size-5 text-violet-200" /></span>
                        <span class="flex-1 text-sm font-medium">任务管理与协作</span>
                        <flux:icon.check-circle class="size-5 text-emerald-300" />
                    </div>
                    <div class="flex items-center gap-4 rounded-2xl border border-white/10 bg-white/5 px-5 py-4">
                        <span class="grid size-9 place-items-center rounded-xl bg-white/10"><flux:icon.users class="size-5 text-violet-200" /></span>
                        <span class="flex-1 text-sm font-medium">灵活的团队工作区</span>
                        <flux:icon.check-circle class="size-5 text-emerald-300" />
                    </div>
                </div>
            </div>
            <p class="relative z-10 text-[11px] text-zinc-400">Built with Laravel, Livewire & Flux UI</p>
        </aside>
    </div>
    @livewireScripts
    @fluxScripts
</body>
</html>