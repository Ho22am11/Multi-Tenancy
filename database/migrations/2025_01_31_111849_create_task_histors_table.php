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
        Schema::create('task_histors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->references('id')->on('tasks');
            $table->foreignId('changed_by')->references('id')->on('users');
            $table->string('old_value');
            $table->string('new_value');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_histors');
    }
};
