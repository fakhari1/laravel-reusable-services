<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Finance\Models\Transaction\AccountingTransaction;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('accounting_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants');
            $table->foreignId('fiscal_year_id')->constrained('accounting_fiscal_years');
            $table->morphs('transaction');
            $table->unsignedBigInteger('code');
            $table->timestamp('date');
            $table->string('type');
            $table->string('method');
            $table->unsignedBigInteger('party_id');
            $table->string('party_type');
            $table->unsignedBigInteger('amount');
            $table->unsignedBigInteger('fee')->default(0);
            $table->unsignedBigInteger('discount')->default(0);
            $table->unsignedBigInteger('tax')->default(0);
            $table->unsignedBigInteger('total_amount');
            $table->foreignId('document_id')->constrained('accounting_documents');
            $table->foreignId('source_account_id')->constrained('accounting_detailed_accounts');
            $table->foreignId('destination_account_id')->constrained('accounting_detailed_accounts');
            $table->foreignId('invoice_id')->nullable()->constrained('accounting_invoices');
            $table->foreignId('creator_id')->constrained('tenant_has_staff');
            $table->string('status')->default(AccountingTransaction::STATUS_DRAFT);
            $table->json('meta_data')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['party_type', 'party_id']);
            $table->index(['type', 'status']);
            $table->index(['date', 'type']);
            $table->index(['fiscal_year_id', 'date']);
            $table->index(['code']);
            $table->index(['invoice_id']);
            $table->index(['method', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('accounting_transactions');
        Schema::enableForeignKeyConstraints();
    }
};
