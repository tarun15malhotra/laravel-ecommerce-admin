<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->string('password');
            $table->boolean('is_active')->default(true);
            $table->boolean('email_verified')->default(false);
            $table->timestamp('email_verified_at')->nullable();
            $table->decimal('total_spent', 12, 2)->default(0);
            $table->integer('order_count')->default(0);
            $table->string('customer_group')->nullable();
            $table->json('preferences')->nullable();
            $table->timestamp('last_order_at')->nullable();
            $table->string('remember_token')->nullable();
            $table->timestamps();
            
            $table->index(['email', 'is_active']);
            $table->index('total_spent');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
