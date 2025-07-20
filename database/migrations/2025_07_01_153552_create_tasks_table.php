<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->index();
            $table->foreignId('parent_id')->nullable()->constrained('tasks')->onDelete('set null');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('task_type')->nullable();
            $table->datetime('due_date')->nullable();
            $table->integer('estimated_time')->nullable();
            $table->string('priority')->nullable();
            $table->integer('order')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
