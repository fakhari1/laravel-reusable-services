<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Finance\Models\Account\AccountingDetailedAccount;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('accounting_detailed_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants');
            $table->string('code');
            $table->string('title');
            $table->string('slug')->nullable();
            $table->foreignId('specific_account_id')->constrained('accounting_specific_accounts');
            $table->foreignId('parent_id')->nullable()->constrained('accounting_detailed_accounts');
            $table->unsignedBigInteger('debit_amount')->default(0);
            $table->unsignedBigInteger('credit_amount')->default(0);
            $table->string('level');
            $table->string('status')->default(AccountingDetailedAccount::STATUS_ACTIVE);
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'slug'], 'slug_uk');
            $table->unique(['tenant_id', 'specific_account_id', 'parent_id', 'code'], 'code_uk');
            $table->unique(['tenant_id', 'specific_account_id', 'parent_id', 'title'], 'title_uk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('accounting_detailed_accounts');
        Schema::enableForeignKeyConstraints();
    }
};
