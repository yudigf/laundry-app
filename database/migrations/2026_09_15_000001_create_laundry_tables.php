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
        Schema::create('customers', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('phone')->index();
            $table->text('address')->nullable();
            $table->timestamps();
        });

        Schema::create('laundry_services', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedInteger('price_per_unit'); // in rupiah (e.g. 7000 / kg)
            $table->string('unit')->default('kg'); // kg, pcs, meter
            $table->unsignedSmallInteger('estimated_hours')->default(24);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('laundry_orders', function (Blueprint $table): void {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->string('status')->default('pending');
            $table->unsignedInteger('subtotal')->default(0);
            $table->unsignedInteger('discount_amount')->default(0);
            $table->unsignedInteger('tax_amount')->default(0);
            $table->unsignedInteger('total_amount')->default(0);
            $table->text('notes')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('laundry_order_id')->constrained('laundry_orders')->cascadeOnDelete();
            $table->foreignId('laundry_service_id')->constrained('laundry_services')->restrictOnDelete();
            $table->decimal('quantity', 8, 2); // e.g. 3.5 kg or 5 pcs
            $table->unsignedInteger('unit_price');
            $table->unsignedInteger('subtotal');
            $table->string('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('laundry_orders');
        Schema::dropIfExists('laundry_services');
        Schema::dropIfExists('customers');
    }
};
