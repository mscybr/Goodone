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
        Schema::create('withdraw_requests', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->enum('method', ['interac', 'bank']);
            $table->string('name')->nullable();
            $table->string('transit')->nullable();
            $table->string('institution')->nullable();
            $table->string('account')->nullable();
            $table->string('email')->nullable();
            $table->decimal('amount', 5, 2);
            $table->integer('status')->default(0)->comment("0=pending, 1=sent, 2=rejected");
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdraw_requests');
    }
};
