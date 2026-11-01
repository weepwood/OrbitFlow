<x-layouts.auth>
    <div class="mb-7"><p class="section-kicker">GET STARTED</p><h1 class="mt-3 text-3xl font-semibold tracking-tight">创建你的工作空间</h1><p class="mt-2 text-sm text-zinc-500">几步即可开启团队协作。</p></div>
    <form action="{{ route('register.perform') }}" method="POST" class="space-y-4">
        @csrf
        <flux:input name="name" label="姓名" value="{{ old('name') }}" placeholder="你的姓名" autocomplete="name" required />
        <flux:input name="email" label="工作邮箱" type="email" value="{{ old('email') }}" placeholder="you@company.com" autocomplete="email" required />
        <flux:input name="workspace_name" label="工作区名称" value="{{ old('workspace_name') }}" placeholder="例如：设计研发团队" required />
        <flux:input name="password" label="密码（至少 12 位）" type="password" autocomplete="new-password" required />
        <flux:input name="password_confirmation" label="确认密码" type="password" autocomplete="new-password" required />
        @if($errors->any())<div class="rounded-xl bg-red-50 p-3 text-sm text-red-700">{{ $errors->first() }}</div>@endif
        <flux:button type="submit" variant="primary" class="w-full">创建账户与工作区</flux:button>
    </form>
    <p class="mt-7 text-center text-sm text-zinc-500">已有账户？ <a href="{{ route('login') }}" class="font-semibold text-indigo-600 hover:underline">直接登录</a></p>
</x-layouts.auth>
