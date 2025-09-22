<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Finance\Models\Transaction\AccountingBankTransaction;

return new
class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('accounting_bank_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants');
            $table->foreignId('fiscal_year_id')->constrained('accounting_fiscal_years');
            $table->string('type')->default(AccountingBankTransaction::TYPE_POS);
            $table->foreignId('source_account_id')->nullable()->constrained('accounting_detailed_accounts');
            $table->foreignId('destination_account_id')->nullable()->constrained('accounting_detailed_accounts');
            $table->string('source_account_number')->nullable();
            $table->string('destination_account_number')->nullable();
            $table->string('source_owner_data')->nullable();
            $table->string('destination_owner_data')->nullable();
            $table->text('meta_data')->nullable();
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
        Schema::dropIfExists('accounting_bank_transactions');
        Schema::enableForeignKeyConstraints();
    }
};
