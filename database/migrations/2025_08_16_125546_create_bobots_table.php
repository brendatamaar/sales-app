<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBobotsTable extends Migration
{
    public function up(): void
    {
        Schema::create('bobots', function (Blueprint $table) {
            $table->bigIncrements('bobot_id');
            $table->string('stage');                 // e.g. mapping/visit/quotation/won
            $table->integer('point')->default(0);
            $table->timestamps();

            $table->unique(['stage', 'point']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('bobots');
    }
}
