<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDealsReportsTable extends Migration
{
    public function up(): void
    {
        Schema::create('deals_reports', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('deals_id', 64);
            $table->string('stage');
            $table->date('created_date')->nullable();
            $table->date('closed_date')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamp('updated_at')->useCurrent();

            // FKs
            $table->foreign('deals_id')
                ->references('deals_id')->on('deals')
                ->onDelete('cascade');

            $table->foreign('updated_by')
                ->references('id')->on('users')
                ->onDelete('set null');

            // Indexes
            $table->index('deals_id');
            $table->index('stage');
            $table->index('updated_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deals_reports');
    }
}
