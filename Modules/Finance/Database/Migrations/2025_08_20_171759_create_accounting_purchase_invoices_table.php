<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Finance\Models\Invoice\AccountingPurchaseInvoice;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('accounting_purchase_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants');
            $table->foreignId('fiscal_year_id')->constrained('accounting_fiscal_years');
            $table->string('code');
            $table->string('type')->default(AccountingPurchaseInvoice::TYPE_CREDIT);
            $table->foreignId('receiver_id')->constrained('tenant_has_staff');
            $table->timestamp('received_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->foreignId('confirmed_by')->constrained('tenant_has_staff');
            $table->string('status')->default(AccountingPurchaseInvoice::STATUS_DRAFT);
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'fiscal_year_id', 'code'], 'code_uk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('accounting_purchase_invoices');
        Schema::disableForeignKeyConstraints();

    }
};
