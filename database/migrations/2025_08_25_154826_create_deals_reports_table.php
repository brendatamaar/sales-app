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

            // match deals.deals_id (string PK)
            $table->string('deals_id', 64);
            $table->string('stage');                 // stage after the change
            $table->unsignedBigInteger('updated_by')->nullable(); // user who caused the change

            // only updated_at as requested
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
