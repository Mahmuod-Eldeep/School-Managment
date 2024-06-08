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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('status', ['Student', 'Teacher', 'Manager'])->default('Manager');
            $table->string('email')->unique();
            $table->string('phoneNumber')->unique();
            $table->string('image_path')->nullable();
            $table->enum('classRoom', include(app_path('Enums/ClassroomLevels.php')));
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('payment_status', ['Pending', 'Paid'])->nullable();
            $table->timestamp('payment_date')->nullable();
            $table->rememberToken();
            $table->timestamps(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
