<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Finance\Models\Account\AccountingSpecificAccount;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('accounting_specific_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants');
            $table->string('code');
            $table->string('title');
            $table->string('slug')->nullable();
            $table->foreignId('general_account_id')->constrained('accounting_general_accounts');
            $table->string('status')->default(AccountingSpecificAccount::STATUS_ACTIVE);
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'general_account_id', 'code'], 'code_uk');
            $table->unique(['tenant_id', 'general_account_id', 'title'], 'title_uk');
            $table->unique(['tenant_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('accounting_specific_accounts');
        Schema::enableForeignKeyConstraints();
    }
};
