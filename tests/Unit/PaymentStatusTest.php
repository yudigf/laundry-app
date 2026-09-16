<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Enums\PaymentStatus;
use PHPUnit\Framework\TestCase;

class PaymentStatusTest extends TestCase
{
    public function test_it_has_expected_values(): void
    {
        $this->assertSame('unpaid', PaymentStatus::Unpaid->value);
        $this->assertSame('paid', PaymentStatus::Paid->value);
    }

    public function test_it_returns_correct_labels(): void
    {
        $this->assertSame('Belum Lunas', PaymentStatus::Unpaid->label());
        $this->assertSame('Lunas', PaymentStatus::Paid->label());
    }
}
