<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Modules\Warehouse\Models\Product;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('dasterang_product_id')->nullable();
            $table->string('code');
            $table->unsignedBigInteger('product_category_id');
            $table->string('name');
            $table->string('main_counting_unit');
            $table->string('sub_counting_unit')->nullable();
            $table->integer('stock_count')->default(0);
            $table->unsignedInteger('coefficient')->default(1);
            $table->enum('status', Product::$statuses)->default(Product::STATUS_ACTIVE);
            $table->string('thumbnail')->nullable();
            $table->string('image')->nullable();
            $table->json('type');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'code']);
            $table->index('tenant_id');
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
