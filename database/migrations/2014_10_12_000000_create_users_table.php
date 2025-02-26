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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('password');
            $table->integer('phone');
            $table->enum('type', ["customer","worker"])->default('customer')->comment('customer/worker');
            $table->string('full_name');
            $table->string('picture');
            $table->timestamp('email_verified_at')->nullable();
            // $table->bigInteger('category_id')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
