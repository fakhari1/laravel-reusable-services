<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('accounting_document_articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants');
            $table->foreignId('document_id')->constrained('accounting_documents');
            $table->foreignId('detailed_account_id')->constrained('accounting_detailed_accounts');
            $table->text('description');
            $table->unsignedBigInteger('debit_amount');
            $table->unsignedBigInteger('credit_amount');
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
        Schema::dropIfExists('accounting_document_articles');
        Schema::enableForeignKeyConstraints();
    }
};
