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
            $table->string('firstname');
            $table->string('lastname');
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('email')->unique();
            $table->enum('gender',['male','female']);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('national_number')->default('12312312312312312');
            $table->string('location')->nullable();
            $table->date('birthday')->nullable();
            $table->enum('role',['user','police','admin'])->default('user');
            $table->string('profile_image')->default("");
            $table->string('phone');
            $table->boolean('block')->default(false);
            $table->string('bio')->nullable();
            $table->string('country')->default('syria');
            $table->string('city')->default('damascus');
            $table->string('street')->default('babAlhara');
            $table->string('education')->nullable();
            $table->string('live')->nullable();
            $table->string('work')->nullable();
            $table->string('badge_number')->nullable();
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
