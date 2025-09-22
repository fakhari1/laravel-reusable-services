<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Warehouse\Models\Warehouse;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants');
            $table->string('name');
            $table->string('code');
            $table->string('type')->nullable();
            $table->foreignId('storekeeper_id')->nullable()->constrained('tenant_has_staff');
            $table->foreignId('address_id')->nullable()->constrained('addresses');
            $table->unsignedBigInteger('account_id')->nullable();
            $table->enum('status', Warehouse::$statuses)->default(Warehouse::STATUS_ACTIVE);
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'code']);
            $table->index('tenant_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('warehouses');
        Schema::enableForeignKeyConstraints();

    }
};
