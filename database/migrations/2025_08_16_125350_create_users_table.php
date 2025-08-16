<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email')->unique();
            $table->string('username')->unique();
            $table->string('password');
            $table->string('role')->nullable();
            $table->string('region')->nullable();
            $table->unsignedBigInteger('store_id')->nullable();
            $table->timestamps();

            $table->foreign('store_id')
                ->references('store_id')->on('stores')
                ->onDelete('set null');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
}
