<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDealsItemsTable extends Migration
{
    public function up(): void
    {
        Schema::create('deals_items', function (Blueprint $table) {
            $table->id();
            $table->string('deals_id', 64);
            $table->unsignedBigInteger('item_no');
            $table->integer('quantity');
            $table->decimal('unit_price', 15, 2);
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->decimal('line_total', 18, 2);
            $table->text('notes')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('deals_id')
                ->references('deals_id')->on('deals')
                ->onDelete('cascade');
            $table->foreign('item_no')
                ->references('item_no')->on('items')
                ->onDelete('restrict'); // Prevent item deletion if used in deals

            // Indexes
            $table->index('deals_id');
            $table->index('item_no');
            $table->unique(['deals_id', 'item_no']); // Prevent duplicate items in same deal
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deals_items');
    }
}