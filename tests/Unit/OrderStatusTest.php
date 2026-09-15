<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Enums\OrderStatus;
use PHPUnit\Framework\TestCase;

class OrderStatusTest extends TestCase
{
    public function test_it_returns_correct_labels(): void
    {
        $this->assertSame('Menunggu Antrean', OrderStatus::Pending->label());
        $this->assertSame('Sedang Dicuci / Diproses', OrderStatus::InProgress->label());
        $this->assertSame('Siap Diambil', OrderStatus::ReadyForPickup->label());
        $this->assertSame('Selesai Diambil', OrderStatus::Completed->label());
        $this->assertSame('Dibatalkan', OrderStatus::Cancelled->label());
    }

    public function test_it_validates_order_transitions(): void
    {
        // Pending can transition to InProgress or Cancelled
        $this->assertTrue(OrderStatus::Pending->canTransitionTo(OrderStatus::InProgress));
        $this->assertTrue(OrderStatus::Pending->canTransitionTo(OrderStatus::Cancelled));
        $this->assertFalse(OrderStatus::Pending->canTransitionTo(OrderStatus::Completed));

        // InProgress can transition to ReadyForPickup or Cancelled
        $this->assertTrue(OrderStatus::InProgress->canTransitionTo(OrderStatus::ReadyForPickup));
        $this->assertTrue(OrderStatus::InProgress->canTransitionTo(OrderStatus::Cancelled));
        $this->assertFalse(OrderStatus::InProgress->canTransitionTo(OrderStatus::Pending));

        // ReadyForPickup can only transition to Completed
        $this->assertTrue(OrderStatus::ReadyForPickup->canTransitionTo(OrderStatus::Completed));
        $this->assertFalse(OrderStatus::ReadyForPickup->canTransitionTo(OrderStatus::Cancelled));

        // Completed cannot transition to anything
        $this->assertFalse(OrderStatus::Completed->canTransitionTo(OrderStatus::Pending));
        $this->assertFalse(OrderStatus::Completed->canTransitionTo(OrderStatus::Cancelled));
    }
}
