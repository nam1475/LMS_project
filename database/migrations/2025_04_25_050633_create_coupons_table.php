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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('description')->nullable();
            $table->string('type'); 
            $table->integer('value'); 
            $table->date('start_date')->nullable();
            $table->date('expire_date')->nullable();
            $table->boolean('status')->default(1);
            // $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
