<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Enums\ServiceType;
use App\Services\OrderCalculationService;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class OrderCalculationServiceTest extends TestCase
{
    private OrderCalculationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new OrderCalculationService;
    }

    public function test_it_calculates_item_subtotal_correctly(): void
    {
        // 3.5 kg at Rp 8.000 / kg = Rp 28.000
        $subtotal = $this->service->calculateItemSubtotal(3.5, 8000);
        $this->assertSame(28000, $subtotal);

        // 2 items at Rp 15.000 = Rp 30.000
        $subtotalItem = $this->service->calculateItemSubtotal(2.0, 15000);
        $this->assertSame(30000, $subtotalItem);
    }

    public function test_it_throws_exception_for_invalid_quantity(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->service->calculateItemSubtotal(0.0, 8000);
    }

    public function test_it_throws_exception_for_negative_price(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->service->calculateItemSubtotal(2.0, -5000);
    }

    public function test_it_calculates_order_totals_with_discount_and_tax(): void
    {
        $items = [
            ['quantity' => 5.0, 'unit_price' => 10000], // 50.000
            ['quantity' => 2.0, 'unit_price' => 25000], // 50.000
        ]; // Subtotal = 100.000

        // Diskon 10%, Pajak 11%
        $result = $this->service->calculateOrderTotals($items, 10.0, 11.0);

        $this->assertSame(100000, $result['subtotal']);
        $this->assertSame(10000, $result['discount_amount']);
        $this->assertSame(9900, $result['tax_amount']); // 11% of 90.000
        $this->assertSame(99900, $result['total_amount']); // 90.000 + 9.900
    }

    public function test_it_gets_correct_price_per_kg(): void
    {
        $this->assertSame(10000, $this->service->getPricePerKg(ServiceType::Standar));
        $this->assertSame(20000, $this->service->getPricePerKg(ServiceType::Express));
    }

    public function test_it_calculates_laundry_price(): void
    {
        // 3.5 kg standar = 35.000
        $this->assertSame(35000, $this->service->calculateLaundryPrice(3.5, ServiceType::Standar));
        // 2.5 kg express = 50.000
        $this->assertSame(50000, $this->service->calculateLaundryPrice(2.5, ServiceType::Express));
    }

    public function test_it_generates_formatted_order_number(): void
    {
        $today = date('Ymd');
        $orderNumber = $this->service->generateOrderNumber(42);

        $this->assertSame("LND-{$today}-0042", $orderNumber);
    }
}
