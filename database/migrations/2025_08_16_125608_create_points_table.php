<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePointsTable extends Migration
{
    public function up(): void
    {
        Schema::create('points', function (Blueprint $table) {
            $table->bigIncrements('point_id');

            $table->string('deals_id', 64);
            $table->enum('stage', ['mapping', 'visit', 'quotation', 'won', 'lost']);
            $table->unsignedBigInteger('salper_id');
            $table->integer('total_points')->default(0);
            $table->timestamps();
            $table->foreign('deals_id')
                ->references('deals_id')->on('deals')
                ->onDelete('cascade');

            $table->foreign('salper_id')
                ->references('salper_id')->on('salpers')
                ->onDelete('cascade');

            $table->unique(['deals_id', 'stage', 'salper_id']);
            $table->index('salper_id');
            $table->index('deals_id');
            $table->index('stage');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('points');
    }
}
