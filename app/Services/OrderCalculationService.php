<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\ServiceType;
use InvalidArgumentException;

class OrderCalculationService
{
    public const int BASE_PRICE_PER_KG = 10000;

    /**
     * Get price per kg based on service type.
     * Standar: Rp 10.000, Express: 2x (Rp 20.000).
     */
    public function getPricePerKg(ServiceType $serviceType): int
    {
        return match ($serviceType) {
            ServiceType::Standar => self::BASE_PRICE_PER_KG,
            ServiceType::Express => self::BASE_PRICE_PER_KG * 2,
        };
    }

    /**
     * Calculate laundry price based on weight and service type.
     */
    public function calculateLaundryPrice(float $weightKg, ServiceType $serviceType): int
    {
        $unitPrice = $this->getPricePerKg($serviceType);

        return $this->calculateItemSubtotal($weightKg, $unitPrice);
    }

    /**
     * Calculate item subtotal based on quantity and unit price.
     */
    public function calculateItemSubtotal(float $quantity, int $unitPrice): int
    {
        if ($quantity <= 0) {
            throw new InvalidArgumentException('Kuantitas cucian harus lebih besar dari 0.');
        }

        if ($unitPrice < 0) {
            throw new InvalidArgumentException('Harga satuan tidak boleh bernilai negatif.');
        }

        return (int) round($quantity * $unitPrice);
    }

    /**
     * Calculate total order with optional discount percentage and tax rate.
     *
     * @param  list<array{quantity: float, unit_price: int}>  $items
     * @return array{subtotal: int, discount_amount: int, tax_amount: int, total_amount: int}
     */
    public function calculateOrderTotals(array $items, float $discountPercentage = 0.0, float $taxPercentage = 0.0): array
    {
        if ($discountPercentage < 0.0 || $discountPercentage > 100.0) {
            throw new InvalidArgumentException('Persentase diskon harus antara 0% dan 100%.');
        }

        if ($taxPercentage < 0.0 || $taxPercentage > 100.0) {
            throw new InvalidArgumentException('Persentase pajak harus antara 0% dan 100%.');
        }

        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += $this->calculateItemSubtotal($item['quantity'], $item['unit_price']);
        }

        $discountAmount = (int) round($subtotal * ($discountPercentage / 100.0));
        $taxableAmount = max(0, $subtotal - $discountAmount);
        $taxAmount = (int) round($taxableAmount * ($taxPercentage / 100.0));
        $totalAmount = $taxableAmount + $taxAmount;

        return [
            'subtotal' => $subtotal,
            'discount_amount' => $discountAmount,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
        ];
    }

    /**
     * Generate a unique human-friendly order invoice number.
     */
    public function generateOrderNumber(int $sequenceNumber): string
    {
        if ($sequenceNumber <= 0) {
            throw new InvalidArgumentException('Nomor urut harus lebih besar dari 0.');
        }

        $datePrefix = date('Ymd');

        return sprintf('LND-%s-%04d', $datePrefix, $sequenceNumber);
    }
}
