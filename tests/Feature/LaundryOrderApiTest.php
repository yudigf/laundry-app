<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
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
                    'payment_status' => PaymentStatus::Unpaid->value,
                    'payment_status_label' => PaymentStatus::Unpaid->label(),
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
            'payment_status' => 'unpaid',
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
                    'payment_status' => PaymentStatus::Unpaid->value,
                    'payment_status_label' => PaymentStatus::Unpaid->label(),
                    'status' => OrderStatus::Pending->label(),
                ],
            ]);

        $this->assertDatabaseHas('laundry_orders', [
            'customer_phone' => '089876543210',
            'service_type' => 'express',
            'unit_price' => 20000,
            'total_amount' => 40000,
            'status' => 'pending',
            'payment_status' => 'unpaid',
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

    public function test_can_list_all_laundry_orders(): void
    {
        $customer = Customer::create([
            'name' => 'Bambang Soediro',
            'phone' => '081344556677',
        ]);

        LaundryOrder::create([
            'order_number' => 'LND-LIST-001',
            'customer_id' => $customer->id,
            'customer_phone' => '081344556677',
            'status' => OrderStatus::Pending,
            'service_type' => 'standar',
            'weight_kg' => 3.0,
            'unit_price' => 10000,
            'total_amount' => 30000,
        ]);

        LaundryOrder::create([
            'order_number' => 'LND-LIST-002',
            'customer_id' => $customer->id,
            'customer_phone' => '081344556677',
            'status' => OrderStatus::InProgress,
            'service_type' => 'express',
            'weight_kg' => 2.0,
            'unit_price' => 20000,
            'total_amount' => 40000,
        ]);

        $response = $this->getJson('/api/orders');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'order_number',
                        'customer_name',
                        'customer_phone',
                        'service_type',
                        'weight_kg',
                        'unit_price',
                        'subtotal',
                        'total_amount',
                        'payment_status',
                        'payment_status_label',
                        'status',
                        'status_label',
                        'created_at',
                    ],
                ],
            ])
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.payment_status', PaymentStatus::Unpaid->value);
    }

    public function test_can_filter_laundry_orders_by_status_with_case_insensitivity(): void
    {
        $customer = Customer::create([
            'name' => 'Rina Nose',
            'phone' => '081299990000',
        ]);

        // 1. Pending
        LaundryOrder::create([
            'order_number' => 'LND-FILTER-001',
            'customer_id' => $customer->id,
            'customer_phone' => '081299990000',
            'status' => OrderStatus::Pending,
            'service_type' => 'standar',
            'weight_kg' => 3.0,
            'unit_price' => 10000,
            'total_amount' => 30000,
        ]);

        // 2. InProgress
        LaundryOrder::create([
            'order_number' => 'LND-FILTER-002',
            'customer_id' => $customer->id,
            'customer_phone' => '081299990000',
            'status' => OrderStatus::InProgress,
            'service_type' => 'express',
            'weight_kg' => 2.5,
            'unit_price' => 20000,
            'total_amount' => 50000,
        ]);

        // 3. Completed
        LaundryOrder::create([
            'order_number' => 'LND-FILTER-003',
            'customer_id' => $customer->id,
            'customer_phone' => '081299990000',
            'status' => OrderStatus::Completed,
            'service_type' => 'standar',
            'weight_kg' => 4.0,
            'unit_price' => 10000,
            'total_amount' => 40000,
        ]);

        // Filter with ?status=Pending (capitalized as requested)
        $responsePending = $this->getJson('/api/orders?status=Pending');
        $responsePending->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.order_number', 'LND-FILTER-001')
            ->assertJsonPath('data.0.status', 'pending');

        // Filter with ?status=completed (lowercase)
        $responseCompleted = $this->getJson('/api/orders?status=completed');
        $responseCompleted->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.order_number', 'LND-FILTER-003')
            ->assertJsonPath('data.0.status', 'completed');

        // Filter with ?status=in_progress
        $responseInProgress = $this->getJson('/api/orders?status=in_progress');
        $responseInProgress->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.order_number', 'LND-FILTER-002')
            ->assertJsonPath('data.0.status', 'in_progress');
    }

    public function test_can_mark_order_as_paid_successfully(): void
    {
        $customer = Customer::create([
            'name' => 'Agus Salim',
            'phone' => '081200001111',
        ]);

        $order = LaundryOrder::create([
            'order_number' => 'LND-PAY-001',
            'customer_id' => $customer->id,
            'customer_phone' => '081200001111',
            'status' => OrderStatus::Pending,
            'payment_status' => PaymentStatus::Unpaid,
            'service_type' => 'standar',
            'weight_kg' => 3.0,
            'unit_price' => 10000,
            'subtotal' => 30000,
            'discount_amount' => 0,
            'tax_amount' => 0,
            'total_amount' => 30000,
        ]);

        $response = $this->patchJson("/api/orders/{$order->id}/payment", [
            'payment_status' => 'paid',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Status pembayaran berhasil diperbarui.',
                'data' => [
                    'id' => $order->id,
                    'order_number' => 'LND-PAY-001',
                    'payment_status' => 'paid',
                    'payment_status_label' => 'Lunas',
                    'payment_method' => 'cash',
                ],
            ]);

        // Verify paid_at is set
        $response->assertJsonStructure([
            'data' => ['paid_at'],
        ]);

        $this->assertNotNull($response->json('data.paid_at'));

        $this->assertDatabaseHas('laundry_orders', [
            'id' => $order->id,
            'payment_status' => 'paid',
            'payment_method' => 'cash',
        ]);

        // Verify paid_at is stored in database
        $order->refresh();
        $this->assertNotNull($order->paid_at);
    }

    public function test_can_revert_payment_back_to_unpaid(): void
    {
        $customer = Customer::create([
            'name' => 'Budi Revert',
            'phone' => '081200002222',
        ]);

        $order = LaundryOrder::create([
            'order_number' => 'LND-PAY-002',
            'customer_id' => $customer->id,
            'customer_phone' => '081200002222',
            'status' => OrderStatus::Pending,
            'payment_status' => PaymentStatus::Paid,
            'paid_at' => now()->format('Y-m-d H:i:s'),
            'payment_method' => 'cash',
            'service_type' => 'express',
            'weight_kg' => 2.0,
            'unit_price' => 20000,
            'subtotal' => 40000,
            'discount_amount' => 0,
            'tax_amount' => 0,
            'total_amount' => 40000,
        ]);

        $response = $this->patchJson("/api/orders/{$order->id}/payment", [
            'payment_status' => 'unpaid',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'payment_status' => 'unpaid',
                    'payment_status_label' => 'Belum Lunas',
                    'paid_at' => null,
                    'payment_method' => null,
                ],
            ]);

        $this->assertDatabaseHas('laundry_orders', [
            'id' => $order->id,
            'payment_status' => 'unpaid',
        ]);
    }

    public function test_update_payment_fails_for_invalid_payment_status(): void
    {
        $customer = Customer::create([
            'name' => 'Invalid Pay',
            'phone' => '081200003333',
        ]);

        $order = LaundryOrder::create([
            'order_number' => 'LND-PAY-003',
            'customer_id' => $customer->id,
            'status' => OrderStatus::Pending,
            'service_type' => 'standar',
            'weight_kg' => 2.0,
            'unit_price' => 10000,
            'total_amount' => 20000,
        ]);

        $response = $this->patchJson("/api/orders/{$order->id}/payment", [
            'payment_status' => 'unknown_payment',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['payment_status']);
    }

    public function test_index_returns_paid_at_and_payment_method(): void
    {
        $customer = Customer::create([
            'name' => 'Indeks Pay',
            'phone' => '081200004444',
        ]);

        LaundryOrder::create([
            'order_number' => 'LND-PAY-IDX-001',
            'customer_id' => $customer->id,
            'customer_phone' => '081200004444',
            'status' => OrderStatus::Pending,
            'payment_status' => PaymentStatus::Paid,
            'paid_at' => '2026-09-16 10:30:00',
            'payment_method' => 'cash',
            'service_type' => 'standar',
            'weight_kg' => 3.0,
            'unit_price' => 10000,
            'total_amount' => 30000,
        ]);

        $response = $this->getJson('/api/orders');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'paid_at',
                        'payment_method',
                        'payment_status',
                        'payment_status_label',
                    ],
                ],
            ])
            ->assertJsonPath('data.0.paid_at', '2026-09-16 10:30:00')
            ->assertJsonPath('data.0.payment_method', 'cash');
    }
}
