<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Models\Customer;
use App\Models\LaundryOrder;
use App\Models\LaundryService;
use App\Models\OrderItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaundryOrderFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_laundry_order_with_items(): void
    {
        $customer = Customer::create([
            'name' => 'Budi Santoso',
            'phone' => '08123456789',
            'address' => 'Jl. Mawar No. 12, Jakarta',
        ]);

        $serviceKiloan = LaundryService::create([
            'name' => 'Cuci Komplit Reguler',
            'description' => 'Cuci, kering, setrika wangi',
            'price_per_unit' => 7000,
            'unit' => 'kg',
            'estimated_hours' => 24,
            'is_active' => true,
        ]);

        $order = LaundryOrder::create([
            'order_number' => 'LND-20260915-0001',
            'customer_id' => $customer->id,
            'status' => OrderStatus::Pending,
            'subtotal' => 21000,
            'discount_amount' => 0,
            'tax_amount' => 0,
            'total_amount' => 21000,
            'notes' => 'Pakaian warna putih dipisahkan',
        ]);

        $orderItem = OrderItem::create([
            'laundry_order_id' => $order->id,
            'laundry_service_id' => $serviceKiloan->id,
            'quantity' => 3.0,
            'unit_price' => 7000,
            'subtotal' => 21000,
            'notes' => '3 kg baju harian',
        ]);

        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'name' => 'Budi Santoso',
        ]);

        $this->assertDatabaseHas('laundry_orders', [
            'id' => $order->id,
            'order_number' => 'LND-20260915-0001',
            'status' => 'pending',
            'total_amount' => 21000,
        ]);

        $this->assertDatabaseHas('order_items', [
            'id' => $orderItem->id,
            'subtotal' => 21000,
        ]);

        // Check relations
        $customerFromOrder = $order->customer;
        $this->assertNotNull($customerFromOrder);
        $this->assertSame('Budi Santoso', $customerFromOrder->name);
        $this->assertCount(1, $order->items);
        $this->assertSame(OrderStatus::Pending, $order->status);

        // Update status to InProgress
        $order->update(['status' => OrderStatus::InProgress]);
        $freshOrder = $order->fresh();
        $this->assertNotNull($freshOrder);
        $this->assertSame(OrderStatus::InProgress, $freshOrder->status);
    }
}
