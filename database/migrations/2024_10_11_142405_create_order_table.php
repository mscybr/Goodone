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
            $table->smallInteger('status')->default(0)->comment("0=unproccessed, 1=pending, 2=completed, 3=canceled");
            $table->bigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->bigInteger('service_id');
            $table->foreign('service_id')->references('id')->on('services');
            $table->string('location');
            $table->decimal('price', 5, 2);
             $table->timestamps();
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
