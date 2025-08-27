<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('quotations', function (Blueprint $t) {
            $t->bigIncrements('id');
            $t->string('quotation_no')->unique();
            $t->string('deals_id');
            $t->date('created_date')->nullable();
            $t->date('expired_date')->nullable();
            $t->integer('valid_days')->nullable();
            $t->string('store_id')->nullable();
            $t->string('store_name')->nullable();
            $t->string('customer_name')->nullable();
            $t->string('no_rek_store')->nullable();
            $t->text('payment_term')->nullable();
            $t->decimal('grand_total', 16, 2)->default(0);
            $t->string('file_path')->nullable();
            $t->json('meta')->nullable();
            $t->timestamps();

            $t->index('deals_id');
            $t->index('created_date');
            $t->index('expired_date');
        });

        Schema::create('quotation_items', function (Blueprint $t) {
            $t->bigIncrements('id');
            $t->unsignedBigInteger('quotation_id');
            $t->integer('row_no')->nullable();
            $t->string('item_no')->nullable();
            $t->string('item_name')->nullable();
            $t->string('uom')->nullable();
            $t->integer('quantity')->default(0);
            $t->decimal('unit_price', 16, 2)->default(0);
            $t->decimal('discount_percent', 5, 2)->default(0);
            $t->decimal('line_total', 16, 2)->default(0);
            $t->timestamps();

            $t->foreign('quotation_id')
                ->references('id')->on('quotations')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotation_items');
        Schema::dropIfExists('quotations');
    }
};
