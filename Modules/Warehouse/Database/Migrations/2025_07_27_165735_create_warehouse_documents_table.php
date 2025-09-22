<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Warehouse\Models\WarehouseDocument;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('warehouse_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants');
            $table->foreignId('staff_id')->constrained('tenant_has_staff');
            $table->foreignId('warehouse_id')->constrained('warehouses');
            $table->string('code');
            $table->enum('type', WarehouseDocument::$types);
            $table->morphs('documentable');
            $table->string('delivery_type');
            $table->enum('status', WarehouseDocument::$statuses)->default(WarehouseDocument::STATUS_PENDING);
            $table->text('description')->nullable();
            $table->timestamp('date');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('warehouse_documents');
        Schema::enableForeignKeyConstraints();

    }
};
