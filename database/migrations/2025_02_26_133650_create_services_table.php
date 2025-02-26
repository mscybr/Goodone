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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('location')->nullable();
            $table->string('license')->nullable();
            $table->decimal('cost_per_hour')->nullable();
            $table->string('service')->nullable();
            $table->boolean('active')->nullable()->default(false);
            $table->integer("years_of_experience")->default(0);
            $table->text("about")->nullable();
            $table->string("country")->nullable();
            $table->string("city")->nullable();
            $table->bigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->bigInteger('subcategory_id');
            $table->foreign('subcategory_id')->references('id')->on('subcategories');
            $table->bigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
