<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Finance\Models\Document\AccountingDocument;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('accounting_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants');
            $table->foreignId('fiscal_year_id')->constrained('accounting_fiscal_years');
            $table->nullableMorphs('subjectable');
            $table->unsignedBigInteger('code');
            $table->date('date');
            $table->foreignId('creator_id')->constrained('tenant_has_staff');
            $table->foreignId('fixer_id')->nullable()->constrained('tenant_has_staff');
            $table->unsignedBigInteger('total_debit_amount');
            $table->unsignedBigInteger('total_credit_amount');
            $table->string('status')->default(AccountingDocument::STATUS_ACTIVE);
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'fiscal_year_id', 'code'], 'tenant_fy_num_ad_uk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('accounting_documents');
        Schema::enableForeignKeyConstraints();
    }
};
