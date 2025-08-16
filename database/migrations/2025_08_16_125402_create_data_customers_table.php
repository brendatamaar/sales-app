<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataCustomersTable extends Migration
{
    public function up(): void
    {
        Schema::create('data_customers', function (Blueprint $table) {
            $table->bigIncrements('id_cust');
            $table->string('cust_name');
            $table->text('cust_address')->nullable();
            $table->string('no_telp_cust', 20)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->unsignedBigInteger('store_id')->nullable();
            $table->timestamps();

            $table->foreign('store_id')
                ->references('store_id')->on('stores')
                ->onDelete('set null');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('data_customers');
    }
}
