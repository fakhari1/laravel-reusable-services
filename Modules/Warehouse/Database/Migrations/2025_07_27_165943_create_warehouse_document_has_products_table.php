<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('warehouse_document_has_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_document_id')->constrained('warehouse_documents');
            $table->foreignId('rack_id')->constrained('racks');
            $table->foreignId('product_id')->constrained('products');
            $table->string('unit');
            $table->unsignedInteger('count');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_document_has_products');
    }
};
