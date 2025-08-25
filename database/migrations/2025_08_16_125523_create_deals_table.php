<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDealsTable extends Migration
{
    public function up(): void
    {
        Schema::create('deals', function (Blueprint $table) {
            $table->string('deals_id', 64)->primary();
            $table->string('deal_name');
            $table->string('stage')->nullable();                 // keep current stage here
            $table->decimal('deal_size', 15, 2)->nullable();
            $table->date('created_date')->nullable();
            $table->date('closed_date')->nullable();

            $table->unsignedBigInteger('store_id')->nullable();
            $table->string('store_name')->nullable();
            $table->string('no_rek_store')->nullable();

            $table->string('email')->nullable();
            $table->text('alamat_lengkap')->nullable();
            $table->text('notes')->nullable();
            $table->json('photo_upload')->nullable();

            $table->unsignedBigInteger('id_cust')->nullable();
            $table->string('cust_name')->nullable();
            $table->string('no_telp_cust', 20)->nullable();

            $table->text('payment_term')->nullable();
            $table->date('quotation_exp_date')->nullable();

            // keep generic artifacts; remove stage-specific owner fields
            $table->json('quotation_upload')->nullable();
            $table->string('receipt_number')->nullable();
            $table->json('receipt_upload')->nullable();

            $table->string('lost_reason')->nullable();

            $table->timestamps();

            // FKs
            $table->foreign('store_id')
                ->references('store_id')->on('stores')
                ->onDelete('set null');

            $table->foreign('id_cust')
                ->references('id_cust')->on('data_customers')
                ->onDelete('set null');

            // Indexes
            $table->index('stage');
            $table->index('created_date');
            $table->index('store_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deals');
    }
}
