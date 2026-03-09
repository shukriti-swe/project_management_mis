<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('label');
            $table->enum('category', ['backlog','todo', 'in_progress', 'done', 'canceled'])->default('backlog');
            $table->string('color')->nullable(); // UI Hex code
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->index(['project_id', 'category']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statuses');
    }
};
