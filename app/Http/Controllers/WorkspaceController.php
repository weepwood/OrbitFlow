<?php
namespace App\Http\Controllers;

use App\Models\Workspace;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkspaceController
{
    public function switch(Request $request): RedirectResponse
    {
        $data = $request->validate(['workspace_id' => ['required', 'integer']]);
        $workspace = $request->user()->workspaces()->whereKey($data['workspace_id'])->firstOrFail();
        $request->user()->update(['current_workspace_id' => $workspace->id]);
        return redirect()->route('dashboard');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:100']]);
        DB::transaction(function () use ($request, $data) {
            $workspace = Workspace::create(['name' => $data['name'], 'owner_id' => $request->user()->id]);
            $workspace->users()->attach($request->user()->id, ['role' => 'owner']);
            $request->user()->update(['current_workspace_id' => $workspace->id]);
        });
        return redirect()->route('dashboard')->with('success', '工作区创建成功。');
    }
}
