<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f7f8fb">
    <meta name="description" content="OrbitFlow 用清晰的项目、任务与工作区管理，让团队协作保持专注。">
    <title>OrbitFlow · 让团队协作回归清晰</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
    @livewireStyles
</head>
<body class="bg-[#f7f8fb] text-zinc-900 antialiased dark:bg-[#101117] dark:text-zinc-100">
    <header class="relative z-20 border-b border-zinc-200/70 bg-white/80 dark:border-zinc-800 dark:bg-zinc-900/80">
        <nav aria-label="主导航" class="mx-auto flex h-[76px] max-w-[1320px] items-center justify-between gap-4 px-5 sm:px-8">
            <a href="{{ route('home') }}" class="flex items-center gap-3" aria-label="OrbitFlow 首页">
                <span class="orbit-mark" aria-hidden="true">OF</span>
                <span class="text-[17px] font-bold tracking-[-.045em]">OrbitFlow</span>
            </a>
            <div class="flex items-center gap-4 sm:gap-7">
                <a href="#features" class="hidden text-xs font-medium text-zinc-500 transition hover:text-indigo-600 md:inline">功能</a>
                <a href="#how-it-works" class="hidden text-xs font-medium text-zinc-500 transition hover:text-indigo-600 md:inline">工作流程</a>
                <a href="#pricing" class="hidden text-xs font-medium text-zinc-500 transition hover:text-indigo-600 md:inline">产品计划</a>
                <a href="{{ route('login') }}" class="text-xs font-semibold text-zinc-600 hover:text-indigo-600 dark:text-zinc-200">登录</a>
                <flux:button :href="route('register')" size="sm" variant="primary" icon:trailing="arrow-up-right">免费开始</flux:button>
            </div>
        </nav>
    </header>

    <main>
        <div class="orbit-hero-glow">
            <section class="mx-auto grid max-w-[1320px] items-center gap-12 px-5 pb-24 pt-20 sm:px-8 lg:grid-cols-[.86fr_1.14fr] lg:gap-10 lg:pb-28 lg:pt-28">
                <div class="relative">
                    <span class="orbit-tag !border-indigo-100 !bg-indigo-50 !text-indigo-700 dark:!border-indigo-900 dark:!bg-indigo-950 dark:!text-indigo-300"><span class="dot !size-1.5 bg-indigo-500" aria-hidden="true"></span> 为专注的团队而设计</span>
                    <h1 class="mt-7 max-w-[640px] text-[48px] font-semibold leading-[1.16] tracking-[-.065em] sm:text-[64px] lg:text-[64px] xl:text-[72px]">
                        让复杂的协作，<br><span class="text-indigo-600 dark:text-indigo-300">回归清晰。</span>
                    </h1>
                    <p class="mt-6 max-w-[470px] text-[15px] leading-8 text-zinc-500 dark:text-zinc-400">把项目、任务和团队组织在一个专注的工作空间。少一点信息噪音，多一点清晰的下一步。</p>
                    <div class="mt-9 flex flex-wrap items-center gap-3">
                        <flux:button :href="route('register')" variant="primary" icon:trailing="arrow-right">创建免费工作区</flux:button>
                        <flux:button :href="route('login')" variant="outline">进入工作台</flux:button>
                    </div>
                    <div class="mt-10 flex flex-wrap items-center gap-x-6 gap-y-3 text-xs text-zinc-500 dark:text-zinc-400">
                        <span class="inline-flex items-center gap-2"><flux:icon.check-circle class="size-4 text-emerald-500" /> 项目进度可见</span>
                        <span class="inline-flex items-center gap-2"><flux:icon.check-circle class="size-4 text-emerald-500" /> 团队协作有序</span>
                        <span class="inline-flex items-center gap-2"><flux:icon.check-circle class="size-4 text-emerald-500" /> 工作区独立</span>
                    </div>
                </div>

                <div class="relative min-w-0 lg:-mr-6" aria-label="OrbitFlow 工作台界面示意">
                    <div class="pointer-events-none absolute -inset-5 -z-10 rounded-[36px] bg-indigo-200/20 blur-[50px] dark:bg-indigo-600/10"></div>
                    <div class="orbit-mock-screen overflow-hidden rounded-[22px] border border-zinc-200/90 bg-white dark:border-zinc-700 dark:bg-zinc-900">
                        <div class="flex h-11 items-center gap-2 border-b border-zinc-100 px-5 dark:border-zinc-800">
                            <span class="size-2.5 rounded-full bg-[#f6aca8]"></span><span class="size-2.5 rounded-full bg-[#f6d891]"></span><span class="size-2.5 rounded-full bg-[#95d7b1]"></span>
                            <span class="mx-auto -translate-x-5 text-[10px] font-medium tracking-wider text-zinc-400">ORBITFLOW / WORKSPACE</span>
                        </div>
                        <div class="grid min-h-[440px] grid-cols-[112px_minmax(0,1fr)] sm:grid-cols-[154px_minmax(0,1fr)]">
                            <aside class="border-r border-zinc-100 bg-[#fcfcff] px-3 py-5 dark:border-zinc-800 dark:bg-zinc-950/60">
                                <div class="flex items-center gap-2 px-2"><span class="grid size-6 place-items-center rounded-lg bg-[#24243c] text-[8px] font-bold text-white">OF</span><span class="hidden text-[11px] font-bold tracking-tight sm:block">OrbitFlow</span></div>
                                <p class="mt-8 px-2 text-[8px] font-semibold uppercase tracking-[.15em] text-zinc-400">Workspace</p>
                                <div class="mt-3 space-y-1">
                                    <div class="flex items-center gap-2 rounded-lg bg-indigo-50 px-2 py-2 text-[10px] font-semibold text-indigo-700 dark:bg-indigo-950 dark:text-indigo-300"><flux:icon.squares-2x2 class="size-3.5" /> 概览</div>
                                    <div class="flex items-center gap-2 rounded-lg px-2 py-2 text-[10px] text-zinc-500"><flux:icon.folder-open class="size-3.5" /> 项目管理</div>
                                    <div class="flex items-center gap-2 rounded-lg px-2 py-2 text-[10px] text-zinc-500"><flux:icon.users class="size-3.5" /> 团队成员</div>
                                </div>
                            </aside>
                            <div class="min-w-0 p-4 sm:p-7">
                                <div class="flex items-start justify-between gap-2">
                                    <div><p class="text-[9px] font-semibold uppercase tracking-widest text-indigo-500">OVERVIEW</p><h2 class="mt-2 text-[18px] font-semibold tracking-tight sm:text-[22px]">工作概览</h2><p class="mt-1 text-[10px] text-zinc-400">让每一步进展更加明确</p></div>
                                    <span class="hidden items-center gap-1.5 rounded-lg bg-[#24243c] px-3 py-2 text-[9px] font-semibold text-white sm:flex"><flux:icon.plus class="size-3" /> 新建项目</span>
                                </div>
                                <div class="mt-6 grid grid-cols-2 gap-2 sm:grid-cols-4">
                                    <div class="rounded-xl border border-zinc-200/70 p-3 dark:border-zinc-700"><p class="text-[9px] text-zinc-400">全部项目</p><p class="mt-2 text-[22px] font-semibold tracking-tight">12</p></div>
                                    <div class="rounded-xl border border-zinc-200/70 p-3 dark:border-zinc-700"><p class="text-[9px] text-zinc-400">进行中</p><p class="mt-2 text-[22px] font-semibold tracking-tight">08</p></div>
                                    <div class="rounded-xl border border-zinc-200/70 p-3 dark:border-zinc-700"><p class="text-[9px] text-zinc-400">任务总数</p><p class="mt-2 text-[22px] font-semibold tracking-tight">64</p></div>
                                    <div class="rounded-xl border border-zinc-200/70 p-3 dark:border-zinc-700"><p class="text-[9px] text-zinc-400">已完成</p><p class="mt-2 text-[22px] font-semibold tracking-tight">41</p></div>
                                </div>
                                <div class="mt-4 rounded-xl border border-zinc-200/70 px-4 py-4 dark:border-zinc-700">
                                    <div class="flex items-center justify-between gap-2 text-[10px]"><span class="font-semibold">整体任务进度</span><span class="font-semibold text-indigo-600 dark:text-indigo-300">64%</span></div>
                                    <div class="orbit-progress-track mt-3"><div class="orbit-progress-fill" style="width:64%"></div></div>
                                </div>
                                <div class="mt-4 rounded-xl border border-zinc-200/70 dark:border-zinc-700">
                                    <div class="flex justify-between border-b border-zinc-100 px-4 py-3 text-[10px] font-semibold dark:border-zinc-800"><span>最近项目</span><span class="text-indigo-600 dark:text-indigo-300">全部项目 ↗</span></div>
                                    <div class="flex items-center gap-2 px-4 py-3">
                                        <span class="grid size-7 shrink-0 place-items-center rounded-lg bg-indigo-50 text-[10px] font-bold text-indigo-600">产</span>
                                        <span class="min-w-0 flex-1 truncate text-[10px] font-medium">产品官网改版</span>
                                        <span class="text-[10px] text-zinc-400">78%</span><span class="size-1.5 rounded-full bg-emerald-500"></span>
                                    </div>
                                    <div class="flex items-center gap-2 border-t border-zinc-100 px-4 py-3 dark:border-zinc-800">
                                        <span class="grid size-7 shrink-0 place-items-center rounded-lg bg-violet-50 text-[10px] font-bold text-violet-600">设</span>
                                        <span class="min-w-0 flex-1 truncate text-[10px] font-medium">设计系统升级</span>
                                        <span class="text-[10px] text-zinc-400">46%</span><span class="size-1.5 rounded-full bg-amber-500"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 text-right text-[10px] text-zinc-400">界面示意 · 展示数据为样例</p>
                </div>
            </section>
        </div>

        <section id="features" class="mx-auto max-w-[1320px] px-5 pb-24 sm:px-8 lg:pb-32">
            <div class="max-w-xl">
                <p class="section-kicker">BUILT FOR CLARITY</p>
                <h2 class="orbit-page-title mt-3 text-3xl font-semibold sm:text-[42px]">让重要的工作，始终清晰。</h2>
                <p class="mt-4 text-sm leading-7 text-zinc-500 dark:text-zinc-400">没有复杂的学习曲线，只有真正需要的项目协作能力。</p>
            </div>
            <div class="mt-10 grid gap-4 md:grid-cols-3">
                <article class="surface orbit-feature-card p-7">
                    <span class="orbit-icon-tile bg-indigo-50 text-indigo-600 dark:bg-indigo-950 dark:text-indigo-300"><flux:icon.folder-open class="size-5" /></span>
                    <h3 class="mt-7 text-lg font-semibold tracking-tight">项目集中管理</h3>
                    <p class="mt-3 text-sm leading-7 text-zinc-500 dark:text-zinc-400">让项目状态、描述、交付日期与任务进度保有统一的视图。</p>
                    <span class="mt-7 inline-block text-[11px] font-semibold text-indigo-600 dark:text-indigo-300">01 / ORGANIZE</span>
                </article>
                <article class="surface orbit-feature-card p-7">
                    <span class="orbit-icon-tile bg-violet-50 text-violet-600 dark:bg-violet-950 dark:text-violet-300"><flux:icon.clipboard-document-check class="size-5" /></span>
                    <h3 class="mt-7 text-lg font-semibold tracking-tight">任务跟踪与分配</h3>
                    <p class="mt-3 text-sm leading-7 text-zinc-500 dark:text-zinc-400">通过任务状态、优先级和负责人，让下一步行动变得明确。</p>
                    <span class="mt-7 inline-block text-[11px] font-semibold text-indigo-600 dark:text-indigo-300">02 / EXECUTE</span>
                </article>
                <article class="surface orbit-feature-card p-7">
                    <span class="orbit-icon-tile bg-emerald-50 text-emerald-600 dark:bg-emerald-950 dark:text-emerald-300"><flux:icon.users class="size-5" /></span>
                    <h3 class="mt-7 text-lg font-semibold tracking-tight">独立团队空间</h3>
                    <p class="mt-3 text-sm leading-7 text-zinc-500 dark:text-zinc-400">按工作区组织团队成员与权限，为不同协作场景清晰划界。</p>
                    <span class="mt-7 inline-block text-[11px] font-semibold text-indigo-600 dark:text-indigo-300">03 / COLLABORATE</span>
                </article>
            </div>
        </section>

        <section id="how-it-works" class="border-y border-zinc-200/70 bg-white py-20 dark:border-zinc-800 dark:bg-zinc-900/70">
            <div class="mx-auto grid max-w-[1320px] gap-10 px-5 sm:px-8 lg:grid-cols-[.75fr_1.25fr] lg:gap-20">
                <div><p class="section-kicker">YOUR WORKFLOW</p><h2 class="orbit-page-title mt-3 text-3xl font-semibold leading-tight sm:text-[40px]">从想法，到真正完成。</h2><p class="mt-4 max-w-md text-sm leading-7 text-zinc-500 dark:text-zinc-400">以轻量但可持续的方式，串联团队的每一个交付步骤。</p></div>
                <div class="grid gap-5 sm:grid-cols-3">
                    <div class="border-l-2 border-indigo-200 pl-5 dark:border-indigo-800"><span class="text-[11px] font-semibold text-indigo-600 dark:text-indigo-300">STEP 01</span><h3 class="mt-4 text-sm font-semibold">创建项目</h3><p class="mt-2 text-xs leading-6 text-zinc-500 dark:text-zinc-400">明确目标、范围与完成期限。</p></div>
                    <div class="border-l-2 border-indigo-200 pl-5 dark:border-indigo-800"><span class="text-[11px] font-semibold text-indigo-600 dark:text-indigo-300">STEP 02</span><h3 class="mt-4 text-sm font-semibold">拆解任务</h3><p class="mt-2 text-xs leading-6 text-zinc-500 dark:text-zinc-400">安排负责人，持续更新状态。</p></div>
                    <div class="border-l-2 border-indigo-200 pl-5 dark:border-indigo-800"><span class="text-[11px] font-semibold text-indigo-600 dark:text-indigo-300">STEP 03</span><h3 class="mt-4 text-sm font-semibold">跟踪交付</h3><p class="mt-2 text-xs leading-6 text-zinc-500 dark:text-zinc-400">从概览中把握进度与重点。</p></div>
                </div>
            </div>
        </section>

        <section id="pricing" class="mx-auto max-w-[1320px] px-5 py-24 sm:px-8">
            <div class="orbit-focus-panel flex flex-col gap-8 p-8 sm:p-12 lg:flex-row lg:items-center lg:justify-between">
                <div class="relative z-10 max-w-2xl">
                    <span class="text-[10px] font-semibold uppercase tracking-[.2em] text-violet-200">START SMALL, THINK BIG</span>
                    <h2 class="mt-4 text-3xl font-semibold tracking-tight sm:text-[40px]">现在就建立你的工作空间。</h2>
                    <p class="mt-4 text-sm leading-7 text-zinc-300">OrbitFlow 当前为 SaaS MVP 演示项目，支持注册及核心协作功能；暂未提供在线支付和商业订阅服务。</p>
                </div>
                <div class="relative z-10 shrink-0"><flux:button :href="route('register')" variant="filled" icon:trailing="arrow-right">开始免费体验</flux:button></div>
            </div>
        </section>
    </main>

    <footer class="border-t border-zinc-200/70 bg-white/60 dark:border-zinc-800 dark:bg-zinc-900/50">
        <div class="mx-auto flex max-w-[1320px] flex-col gap-4 px-5 py-8 text-xs text-zinc-500 sm:flex-row sm:items-center sm:justify-between sm:px-8">
            <span>© {{ date('Y') }} OrbitFlow. Focus on what matters.</span>
            <span>Laravel · Livewire · Flux UI · Tailwind CSS</span>
        </div>
    </footer>
    @livewireScripts
    @fluxScripts
</body>
</html>