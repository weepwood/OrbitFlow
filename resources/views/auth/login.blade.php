<x-layouts.auth>
    <div class="mb-9"><p class="section-kicker">WELCOME BACK</p><h1 class="mt-3 text-3xl font-semibold tracking-tight">登录工作空间</h1><p class="mt-2 text-sm text-zinc-500">输入你的账户信息，继续高效协作。</p></div>
    <form action="{{ route('login.perform') }}" method="POST" class="space-y-5">
        @csrf
        <flux:input name="email" label="邮箱地址" type="email" autocomplete="email" placeholder="you@company.com" value="{{ old('email') }}" required />
        <flux:input name="password" label="登录密码" type="password" autocomplete="current-password" placeholder="请输入密码" required />
        @if($errors->any())<div class="rounded-xl bg-red-50 p-3 text-sm text-red-700">{{ $errors->first() }}</div>@endif
        <div class="flex items-center justify-between"><flux:checkbox name="remember" label="记住我" /><span class="text-xs text-zinc-400">请勿在公共设备保存密码</span></div>
        <flux:button type="submit" variant="primary" class="w-full">登录 <flux:icon.arrow-right class="ml-1 size-4" /></flux:button>
    </form>
    <p class="mt-8 text-center text-sm text-zinc-500">还没有账户？ <a href="{{ route('register') }}" class="font-semibold text-indigo-600 hover:underline">免费注册</a></p>
</x-layouts.auth>
