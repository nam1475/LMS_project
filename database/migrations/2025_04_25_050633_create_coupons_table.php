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
            $table->unsignedBigInteger('minimum_order_amount')->default(0);
            $table->date('start_date')->nullable();
            $table->date('expire_date')->nullable();
            $table->enum('is_approved', ['pending', 'approved', 'rejected'])->default('pending');
            // $table->foreignId('student_id')->nullable()->constrained('users')->onDelete('cascade');
            // $table->foreignId('course_category_id')->nullable()->constrained('course_categories')->onDelete('cascade');
            $table->foreignId('instructor_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->boolean('status')->default(1);
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
