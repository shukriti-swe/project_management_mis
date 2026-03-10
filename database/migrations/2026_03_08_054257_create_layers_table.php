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
        Schema::create('layers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();

            $table->foreignId('project_id')->constrained()->onDelete('cascade');

            $table->foreignId('status_id')->nullable()->constrained('statuses')->onDelete('set null');
            $table->integer('progress_percent')->default(0);
            $table->unsignedInteger('total_tasks')->default(0);
            $table->unsignedInteger('completed_tasks')->default(0);

            $table->enum('type', ['container', 'task'])->default('container');
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->nestedSet(); // For nested set hierarchy
            $table->timestamps();

            $table->index(['project_id', '_lft', '_rgt']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('layers');
    }
};
