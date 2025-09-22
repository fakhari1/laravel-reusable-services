<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Finance\Models\Invoice\AccountingInvoice;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('accounting_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants');
            $table->foreignId('fiscal_year_id')->constrained('accounting_fiscal_years');
            $table->foreignId('creator_id')->constrained('tenant_has_staff');
            $table->foreignId('document_id')->constrained('accounting_documents');
            $table->date('date');
            $table->unsignedBigInteger('subtotal_amount')->default(0);
            $table->unsignedBigInteger('discount_amount')->default(0);
            $table->unsignedTinyInteger('discount_percentage')->default(0);
            $table->unsignedBigInteger('tax_amount')->default(0);
            $table->unsignedTinyInteger('tax_percentage')->default(0);
            $table->unsignedBigInteger('total_amount')->default(0);
            $table->unsignedBigInteger('paid_amount')->default(0);
            $table->unsignedBigInteger('remaining_amount')->default(0);
            $table->morphs('invoiceable');
            $table->string('type')->default(AccountingInvoice::TYPE_SALE);
            $table->foreignId('file_id')->constrained('files');
            $table->string('status')->default(AccountingInvoice::STATUS_CONFIRMED);
            $table->json('meta_data')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'date']);
            $table->index(['date', 'status']);
            $table->index(['fiscal_year_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('accounting_invoices');
        Schema::enableForeignKeyConstraints();
    }
};
