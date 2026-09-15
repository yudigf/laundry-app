<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Models\Customer;
use App\Models\LaundryOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaundryOrderApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_order_with_standar_service_type_successfully(): void
    {
        $payload = [
            'customer_name' => 'Ahmad Dahlan',
            'customer_phone' => '081234567890',
            'weight_kg' => 3.5,
            'service_type' => 'standar',
        ];

        // 3.5 kg * Rp 10.000 = Rp 35.000
        $response = $this->postJson('/api/orders', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Pesanan berhasil dibuat.',
                'data' => [
                    'customer_name' => 'Ahmad Dahlan',
                    'customer_phone' => '081234567890',
                    'service_type' => 'standar',
                    'weight_kg' => 3.5,
                    'unit_price' => 10000,
                    'total_amount' => 35000,
                    'status' => OrderStatus::Pending->label(),
                ],
            ]);

        $this->assertDatabaseHas('customers', [
            'name' => 'Ahmad Dahlan',
            'phone' => '081234567890',
        ]);

        $this->assertDatabaseHas('laundry_orders', [
            'customer_phone' => '081234567890',
            'service_type' => 'standar',
            'total_amount' => 35000,
            'status' => 'pending',
        ]);
    }

    public function test_can_create_order_with_express_service_type_doubles_the_price(): void
    {
        $payload = [
            'customer_name' => 'Siti Nurhaliza',
            'customer_phone' => '089876543210',
            'weight_kg' => 2.0,
            'service_type' => 'express',
        ];

        // 2.0 kg * Rp 20.000 (2x Rp 10.000) = Rp 40.000
        $response = $this->postJson('/api/orders', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Pesanan berhasil dibuat.',
                'data' => [
                    'customer_name' => 'Siti Nurhaliza',
                    'customer_phone' => '089876543210',
                    'service_type' => 'express',
                    'weight_kg' => 2.0,
                    'unit_price' => 20000,
                    'total_amount' => 40000,
                    'status' => OrderStatus::Pending->label(),
                ],
            ]);

        $this->assertDatabaseHas('laundry_orders', [
            'customer_phone' => '089876543210',
            'service_type' => 'express',
            'unit_price' => 20000,
            'total_amount' => 40000,
            'status' => 'pending',
        ]);
    }

    public function test_validation_fails_when_weight_is_under_two_kg(): void
    {
        $payload = [
            'customer_name' => 'Budi',
            'customer_phone' => '0811223344',
            'weight_kg' => 1.8,
            'service_type' => 'standar',
        ];

        $response = $this->postJson('/api/orders', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['weight_kg']);
    }

    public function test_validation_fails_when_required_fields_are_missing(): void
    {
        $response = $this->postJson('/api/orders', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['customer_name', 'customer_phone', 'weight_kg', 'service_type']);
    }

    public function test_validation_fails_for_invalid_service_type(): void
    {
        $payload = [
            'customer_name' => 'Dewi',
            'customer_phone' => '0812345678',
            'weight_kg' => 4.0,
            'service_type' => 'super_fast', // invalid
        ];

        $response = $this->postJson('/api/orders', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['service_type']);
    }

    public function test_can_update_order_status_successfully(): void
    {
        $customer = Customer::create([
            'name' => 'Joko Widodo',
            'phone' => '081299887766',
        ]);

        $order = LaundryOrder::create([
            'order_number' => 'LND-TEST-STATUS-001',
            'customer_id' => $customer->id,
            'customer_phone' => '081299887766',
            'status' => OrderStatus::Pending,
            'service_type' => 'standar',
            'weight_kg' => 3.0,
            'unit_price' => 10000,
            'subtotal' => 30000,
            'discount_amount' => 0,
            'tax_amount' => 0,
            'total_amount' => 30000,
        ]);

        // 1. Update from Pending to InProgress via PATCH
        $responsePatch = $this->patchJson("/api/orders/{$order->id}/status", [
            'status' => 'in_progress',
        ]);

        $responsePatch->assertStatus(200)
            ->assertJson([
                'message' => 'Status pesanan berhasil diperbarui.',
                'data' => [
                    'id' => $order->id,
                    'status' => 'in_progress',
                    'status_label' => OrderStatus::InProgress->label(),
                ],
            ]);

        $this->assertDatabaseHas('laundry_orders', [
            'id' => $order->id,
            'status' => 'in_progress',
        ]);

        // 2. Update to Completed via PUT
        $responsePut = $this->putJson("/api/orders/{$order->id}/status", [
            'status' => 'completed',
        ]);

        $responsePut->assertStatus(200)
            ->assertJson([
                'message' => 'Status pesanan berhasil diperbarui.',
                'data' => [
                    'id' => $order->id,
                    'status' => 'completed',
                    'status_label' => OrderStatus::Completed->label(),
                ],
            ]);

        $this->assertDatabaseHas('laundry_orders', [
            'id' => $order->id,
            'status' => 'completed',
        ]);
    }

    public function test_update_status_fails_for_invalid_status(): void
    {
        $customer = Customer::create([
            'name' => 'Mega',
            'phone' => '0812345678',
        ]);

        $order = LaundryOrder::create([
            'order_number' => 'LND-TEST-STATUS-002',
            'customer_id' => $customer->id,
            'status' => OrderStatus::Pending,
            'service_type' => 'standar',
            'weight_kg' => 2.0,
            'unit_price' => 10000,
            'total_amount' => 20000,
        ]);

        $response = $this->patchJson("/api/orders/{$order->id}/status", [
            'status' => 'unknown_status',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['status']);
    }
}
