<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Modules\Warehouse\Models\WarehouseDocument;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('warehouse_receipt_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->constrained('warehouses');
            $table->foreignId('receiver_id')->constrained('tenant_has_staff');
            $table->string('deliverer');
            $table->enum('status', WarehouseDocument::$statuses)->default(WarehouseDocument::STATUS_PENDING);
            $table->text('description')->nullable();
            $table->timestamp('issuance_date');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_receipt_documents');
    }
};
