<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\OrderStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaundryOrderApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_order_with_standar_service_type_successfully(): void
    {
        $payload = [
            'customer_name' => 'Ahmad Dahlan',
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
                    'service_type' => 'standar',
                    'weight_kg' => 3.5,
                    'unit_price' => 10000,
                    'total_amount' => 35000,
                    'status' => OrderStatus::Pending->label(),
                ],
            ]);

        $this->assertDatabaseHas('customers', [
            'name' => 'Ahmad Dahlan',
        ]);

        $this->assertDatabaseHas('laundry_orders', [
            'service_type' => 'standar',
            'total_amount' => 35000,
            'status' => 'pending',
        ]);
    }

    public function test_can_create_order_with_express_service_type_doubles_the_price(): void
    {
        $payload = [
            'customer_name' => 'Siti Nurhaliza',
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
                    'service_type' => 'express',
                    'weight_kg' => 2.0,
                    'unit_price' => 20000,
                    'total_amount' => 40000,
                    'status' => OrderStatus::Pending->label(),
                ],
            ]);

        $this->assertDatabaseHas('laundry_orders', [
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
            ->assertJsonValidationErrors(['customer_name', 'weight_kg', 'service_type']);
    }

    public function test_validation_fails_for_invalid_service_type(): void
    {
        $payload = [
            'customer_name' => 'Dewi',
            'weight_kg' => 4.0,
            'service_type' => 'super_fast', // invalid
        ];

        $response = $this->postJson('/api/orders', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['service_type']);
    }
}
