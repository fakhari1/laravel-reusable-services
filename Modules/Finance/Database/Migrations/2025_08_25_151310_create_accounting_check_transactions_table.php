<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('accounting_check_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants');
            $table->foreignId('fiscal_year_id')->constrained('accounting_fiscal_years');
            $table->unsignedBigInteger('code');
            $table->foreignId('bank_account_id')->constrained('accounting_detailed_accounts');
            $table->timestamp('issuance_at');
            $table->timestamp('due_at');
            $table->string('number')->nullable();
            $table->foreignId('source_account_id')->nullable()->constrained('accounting_detailed_accounts');
            $table->string('serial_number')->nullable();
            $table->unsignedBigInteger('ownerable_id')->nullable();
            $table->string('ownerable_type')->nullable();
            $table->text('owner_information')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('accounting_check_transactions');
        Schema::enableForeignKeyConstraints();
    }
};
