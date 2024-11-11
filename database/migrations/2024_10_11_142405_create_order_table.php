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
        Schema::disableForeignKeyConstraints();

        Schema::create('order', function (Blueprint $table) {
            $table->id();
            $table->integer('total_hours');
            $table->integer('start_at');
            $table->string('note');
            $table->smallInteger('status')->default(0);
            $table->bigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->bigInteger('service_id');
            $table->foreign('service_id')->references('id')->on('users');
            $table->integer('completion_percentage')->default(0);
            $table->string('location');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order');
    }
};
