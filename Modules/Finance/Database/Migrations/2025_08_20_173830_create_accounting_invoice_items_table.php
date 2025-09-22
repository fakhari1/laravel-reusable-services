<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Central\Models\DefaultQuantityUnit;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('accounting_invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants');
            $table->foreignId('invoice_id')->constrained('accounting_invoices');
            $table->morphs('invoice_itemable', 'invoice_items_morph_index');
            $table->string('unit')->default(DefaultQuantityUnit::UNIT_NUMBER);
            $table->unsignedBigInteger('unit_price');
            $table->unsignedBigInteger('total_price');
            $table->unsignedBigInteger('discount_amount')->default(0);
            $table->unsignedTinyInteger('discount_percentage')->default(0);
            $table->unsignedBigInteger('tax_amount')->default(0);
            $table->unsignedBigInteger('tax_percentage')->default(0);
            $table->unsignedBigInteger('additional_costs')->default(0);
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
        Schema::dropIfExists('accounting_invoice_items');
        Schema::enableForeignKeyConstraints();
    }
};
