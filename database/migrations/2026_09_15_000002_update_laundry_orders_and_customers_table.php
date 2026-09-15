<?php

declare(strict_types=1);

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
        Schema::table('customers', function (Blueprint $table): void {
            $table->string('phone')->nullable()->change();
        });

        Schema::table('laundry_orders', function (Blueprint $table): void {
            $table->string('service_type')->default('standar')->after('status');
            $table->decimal('weight_kg', 8, 2)->default(0)->after('service_type');
            $table->unsignedInteger('unit_price')->default(10000)->after('weight_kg');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laundry_orders', function (Blueprint $table): void {
            $table->dropColumn(['service_type', 'weight_kg', 'unit_price']);
        });

        Schema::table('customers', function (Blueprint $table): void {
            $table->string('phone')->nullable(false)->change();
        });
    }
};
