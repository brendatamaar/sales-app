<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalpersTable extends Migration
{
    public function up(): void
    {
        Schema::create('salpers', function (Blueprint $table) {
            $table->bigIncrements('salper_id');
            $table->string('salper_name');
            $table->unsignedBigInteger('store_id')->nullable();
            $table->timestamps();

            $table->foreign('store_id')
                ->references('store_id')->on('stores')
                ->onDelete('set null');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('salpers');
    }
}
