<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Warehouse\Models\Product;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants');
            $table->unsignedBigInteger('dasterang_product_id')->nullable();
            $table->string('code');
            $table->foreignId('product_category_id')->constrained('product_categories');
            $table->string('name');
            $table->decimal('beginning_inventory')->default(0);
            $table->string('main_counting_unit');
            $table->decimal('coefficient', 12, 2)->default(1);
            $table->string('sub_counting_unit')->nullable();
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
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('products');
        Schema::enableForeignKeyConstraints();

    }
};
