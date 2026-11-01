<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workspace_id')->constrained()->cascadeOnDelete();
            $table->foreignId('creator_id')->constrained('users')->restrictOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('status', 24)->default('planning');
            $table->date('due_date')->nullable();
            $table->timestamps();
            $table->index(['workspace_id', 'status']);
        });
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('assignee_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('status', 24)->default('todo');
            $table->string('priority', 24)->default('medium');
            $table->date('due_date')->nullable();
            $table->timestamps();
            $table->index(['project_id', 'status']);
        });
    }
    public function down(): void { Schema::dropIfExists('tasks'); Schema::dropIfExists('projects'); }
};
