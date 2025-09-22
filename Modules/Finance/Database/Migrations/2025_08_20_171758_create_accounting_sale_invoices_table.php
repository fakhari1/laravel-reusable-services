<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Finance\Models\Invoice\AccountingSaleInvoice;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('accounting_sale_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants');
            $table->foreignId('fiscal_year_id')->constrained('accounting_fiscal_years');
            $table->string('code');
            $table->foreignId('customer_account_id')->constrained('accounting_detailed_accounts');
            $table->foreignId('sale_account_id')->constrained('accounting_detailed_accounts');
            $table->foreignId('value_added_account_id')->constrained('accounting_detailed_accounts');
            $table->foreignId('marketer_account_id')->constrained('accounting_detailed_accounts');
            $table->foreignId('marketing_account_id')->constrained('accounting_detailed_accounts');
            $table->foreignId('payment_account_id')->constrained('accounting_detailed_accounts');
            $table->foreignId('discount_account_id')->constrained('accounting_detailed_accounts');
            $table->string('type')->default(AccountingSaleInvoice::TYPE_CREDIT);
            $table->foreignId('customer_id')->constrained('tenant_has_customers');
            $table->morphs('subjectable');
            $table->string('status')->default(AccountingSaleInvoice::STATUS_DRAFT);
            $table->timestamp('delivery_at')->nullable();
            $table->foreignId('deliverer_id')->constrained('tenant_has_staff');
            $table->timestamp('confirmed_at')->nullable();
            $table->foreignId('confirmed_by')->nullable()->constrained('tenant_has_staff');
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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('accounting_sale_invoices');
    }
};
