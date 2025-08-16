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

            $table->string('deals_id', 64)->nullable();
            $table->unsignedBigInteger('salper_id_mapping')->nullable();

            // mapping
            $table->unsignedBigInteger('bobot_mapping_id')->nullable();
            $table->integer('bobot_mapping')->nullable();

            // visit
            $table->unsignedBigInteger('salper_id_visit')->nullable();
            $table->unsignedBigInteger('bobot_id_visit')->nullable();
            $table->integer('bobot_visit')->nullable();

            // quotation
            $table->unsignedBigInteger('salper_id_quotation')->nullable();
            $table->unsignedBigInteger('bobot_id_quotation')->nullable();
            $table->integer('bobot_quotation')->nullable();

            // won
            $table->unsignedBigInteger('salper_id_won')->nullable();
            $table->unsignedBigInteger('bobot_id_won')->nullable();
            $table->integer('bobot_won')->nullable();

            $table->integer('total_point')->default(0);

            $table->timestamps();

            // FKs
            $table->foreign('deals_id')
                ->references('deals_id')->on('deals')
                ->onDelete('cascade');

            $table->foreign('salper_id_mapping')
                ->references('salper_id')->on('salpers')
                ->onDelete('set null');

            $table->foreign('salper_id_visit')
                ->references('salper_id')->on('salpers')
                ->onDelete('set null');
            $table->foreign('salper_id_quotation')
                ->references('salper_id')->on('salpers')
                ->onDelete('set null');
            $table->foreign('salper_id_won')
                ->references('salper_id')->on('salpers')
                ->onDelete('set null');

            $table->foreign('bobot_mapping_id')
                ->references('bobot_id')->on('bobots')
                ->onDelete('set null');
            $table->foreign('bobot_id_visit')
                ->references('bobot_id')->on('bobots')
                ->onDelete('set null');
            $table->foreign('bobot_id_quotation')
                ->references('bobot_id')->on('bobots')
                ->onDelete('set null');
            $table->foreign('bobot_id_won')
                ->references('bobot_id')->on('bobots')
                ->onDelete('set null');

            $table->index('deals_id');
            $table->index('salper_id_mapping');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('points');
    }
}
