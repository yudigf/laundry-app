<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Enums\OrderStatus;
use App\Enums\ServiceType;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLaundryOrderRequest;
use App\Models\Customer;
use App\Models\LaundryOrder;
use App\Models\LaundryService;
use App\Models\OrderItem;
use App\Services\OrderCalculationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class LaundryOrderController extends Controller
{
    public function __construct(
        protected OrderCalculationService $calculationService,
    ) {}

    /**
     * Create a new laundry order.
     */
    public function store(StoreLaundryOrderRequest $request): JsonResponse
    {
        /** @var array{customer_name: string, weight_kg: float|int|string, service_type: string} $validated */
        $validated = $request->validated();

        $customerName = trim($validated['customer_name']);
        $weightKg = (float) $validated['weight_kg'];
        $serviceType = ServiceType::from($validated['service_type']);

        $unitPrice = $this->calculationService->getPricePerKg($serviceType);
        $totalAmount = $this->calculationService->calculateLaundryPrice($weightKg, $serviceType);

        $order = DB::transaction(function () use ($customerName, $weightKg, $serviceType, $unitPrice, $totalAmount): LaundryOrder {
            $customer = Customer::firstOrCreate(
                ['name' => $customerName]
            );

            $orderCount = LaundryOrder::count() + 1;
            $orderNumber = $this->calculationService->generateOrderNumber($orderCount);

            $order = LaundryOrder::create([
                'order_number' => $orderNumber,
                'customer_id' => $customer->id,
                'status' => OrderStatus::Pending,
                'service_type' => $serviceType->value,
                'weight_kg' => $weightKg,
                'unit_price' => $unitPrice,
                'subtotal' => $totalAmount,
                'discount_amount' => 0,
                'tax_amount' => 0,
                'total_amount' => $totalAmount,
            ]);

            $service = LaundryService::firstOrCreate(
                ['name' => $serviceType === ServiceType::Express ? 'Cuci Express' : 'Cuci Standar'],
                [
                    'description' => $serviceType === ServiceType::Express ? 'Layanan Cuci Cepat Express' : 'Layanan Cuci Reguler Standar',
                    'price_per_unit' => $unitPrice,
                    'unit' => 'kg',
                    'estimated_hours' => $serviceType === ServiceType::Express ? 6 : 24,
                    'is_active' => true,
                ]
            );

            OrderItem::create([
                'laundry_order_id' => $order->id,
                'laundry_service_id' => $service->id,
                'quantity' => $weightKg,
                'unit_price' => $unitPrice,
                'subtotal' => $totalAmount,
            ]);

            return $order;
        });

        return response()->json([
            'message' => 'Pesanan berhasil dibuat.',
            'data' => [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'customer_name' => $customerName,
                'service_type' => $serviceType->value,
                'weight_kg' => $weightKg,
                'unit_price' => $unitPrice,
                'total_amount' => $totalAmount,
                'status' => OrderStatus::Pending->label(),
            ],
        ], 201);
    }
}
