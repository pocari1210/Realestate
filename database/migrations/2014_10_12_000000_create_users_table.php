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
            $table->string('username')->nullable(); //追記
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('photo')->nullable(); //追記
            $table->string('phone')->nullable(); //追記
            $table->text('address')->nullable(); //追記
            $table->integer('credit')->default(0); //追記
            $table->enum('role', ['admin', 'agent', 'user'])->default('user'); //追記
            $table->enum('status', ['active', 'inactive'])->default('active'); //追記
            $table->rememberToken();
            $table->timestamps();
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
