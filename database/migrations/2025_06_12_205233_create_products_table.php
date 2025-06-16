<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Ramsey\Uuid\v1;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('variant_id')->nullable();
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->string('name')->nullable();
            $table->text('description')->nullable();

            $table->text('sku')->nullable();
            $table->text('barcode')->nullable();
            $table->integer('stock')->nullable();
            $table->decimal('list_price, 10, 2')->nullable();
            $table->decimal('cost_unit, 10, 2')->nullable();
            $table->decimal('total_value, 10, 2')->nullable();
            $table->decimal('potencial_revenue, 10, 2')->nullable();
            $table->decimal('potencial_profit, 10, 2')->nullable();
            $table->decimal('profit_margin, 10, 2')->nullable();
            $table->decimal('markup, 10, 2')->nullable();


            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade')->nullable();
            $table->foreign('variant_id')->references('id')->on('variants')->onDelete('cascade')->nullable();
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
