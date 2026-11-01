<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable = ['workspace_id', 'name', 'description', 'status', 'due_date', 'creator_id'];
    protected function casts(): array { return ['due_date' => 'date']; }
    public function workspace(): BelongsTo { return $this->belongsTo(Workspace::class); }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'creator_id'); }
    public function tasks(): HasMany { return $this->hasMany(Task::class); }
}
