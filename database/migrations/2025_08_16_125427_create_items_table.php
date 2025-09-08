<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->bigIncrements('item_no');
            $table->string('item_code')->nullable();
            $table->string('item_name');
            $table->string('category');
            $table->string('uom', 20)->nullable();
            $table->decimal('price', 15, 2)->default(0);
            $table->decimal('disc', 5, 2)->nullable(); // % discount
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
}
