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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title', 50 );
            $table->string('description', 255 );
            $table->enum('state', [ 1 , 2 , 3]);
            $table->enum('Priority', [ 1 , 2 , 3]);
            $table->date('Due_Date');
            $table->foreignId('tenant_id')->nullable()->references('id')->on('tenants');
            $table->foreignId('create_by')->nullable()->references('id')->on('users');
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
