<?php
namespace App\Support;

use App\Models\User;
use App\Models\Workspace;

final class WorkspaceContext
{
    public static function current(?User $user = null): Workspace
    {
        $user ??= auth()->user();
        abort_unless($user, 401);
        // Never trust the raw current_workspace_id without a membership check.
        $workspace = $user->workspaces()->whereKey($user->current_workspace_id)->first();
        abort_unless($workspace, 403, '当前工作区不可访问');
        return $workspace;
    }

    public static function canManageMembers(?User $user = null): bool
    {
        return in_array(self::current($user)->pivot->role, ['owner', 'admin'], true);
    }
}
