<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\ServiceType;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLaundryOrderRequest;
use App\Http\Requests\UpdateOrderStatusRequest;
use App\Http\Requests\UpdatePaymentStatusRequest;
use App\Models\Customer;
use App\Models\LaundryOrder;
use App\Models\LaundryService;
use App\Models\OrderItem;
use App\Services\OrderCalculationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaundryOrderController extends Controller
{
    public function __construct(
        protected OrderCalculationService $calculationService,
    ) {}

    /**
     * Display a listing of laundry orders, optionally filtered by status.
     */
    public function index(Request $request): JsonResponse
    {
        $query = LaundryOrder::with('customer')->latest();

        $statusParam = $request->query('status');
        if (is_string($statusParam) && trim($statusParam) !== '') {
            $normalizedStatus = strtolower(trim($statusParam));
            $query->whereRaw('LOWER(status) = ?', [$normalizedStatus]);
        }

        $orders = $query->get()->map(function (LaundryOrder $order): array {
            return [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'customer_name' => $order->customer?->name,
                'customer_phone' => $order->customer_phone ?? $order->customer?->phone,
                'service_type' => $order->service_type,
                'weight_kg' => (float) $order->weight_kg,
                'unit_price' => (int) $order->unit_price,
                'subtotal' => (int) $order->subtotal,
                'total_amount' => (int) $order->total_amount,
                'payment_status' => $order->payment_status->value,
                'payment_status_label' => $order->payment_status->label(),
                'paid_at' => $order->paid_at?->format('Y-m-d H:i:s'),
                'payment_method' => $order->payment_method,
                'status' => $order->status->value,
                'status_label' => $order->status->label(),
                'created_at' => $order->created_at?->toIso8601String(),
            ];
        });

        return response()->json([
            'message' => 'Daftar pesanan berhasil diambil.',
            'data' => $orders,
        ]);
    }

    /**
     * Create a new laundry order.
     */
    public function store(StoreLaundryOrderRequest $request): JsonResponse
    {
        /** @var array{customer_name: string, weight_kg: float|int|string, service_type: string} $validated */
        $validated = $request->validated();

        $customerName = trim($validated['customer_name']);
        $customerPhone = $request->getCustomerPhone();
        $weightKg = (float) $validated['weight_kg'];
        $serviceType = ServiceType::from($validated['service_type']);

        $unitPrice = $this->calculationService->getPricePerKg($serviceType);
        $totalAmount = $this->calculationService->calculateLaundryPrice($weightKg, $serviceType);

        $order = DB::transaction(function () use ($customerName, $customerPhone, $weightKg, $serviceType, $unitPrice, $totalAmount): LaundryOrder {
            $customer = Customer::firstOrCreate(
                ['name' => $customerName],
                ['phone' => $customerPhone]
            );

            if ($customerPhone !== '' && $customer->phone !== $customerPhone) {
                $customer->update(['phone' => $customerPhone]);
            }

            $orderCount = LaundryOrder::count() + 1;
            $orderNumber = $this->calculationService->generateOrderNumber($orderCount);

            $order = LaundryOrder::create([
                'order_number' => $orderNumber,
                'customer_id' => $customer->id,
                'customer_phone' => $customerPhone,
                'payment_status' => PaymentStatus::ngutang_dulu,
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
                'customer_phone' => $customerPhone,
                'service_type' => $serviceType->value,
                'weight_kg' => $weightKg,
                'unit_price' => $unitPrice,
                'total_amount' => $totalAmount,
                'payment_status' => PaymentStatus::Unpaid->value,
                'payment_status_label' => PaymentStatus::Unpaid->label(),
                'status' => OrderStatus::Pending->label(),
            ],
        ], 201);
    }

    /**
     * Update the status of an existing laundry order.
     */
    public function updateStatus(UpdateOrderStatusRequest $request, LaundryOrder $order): JsonResponse
    {
        /** @var array{status: string} $validated */
        $validated = $request->validated();

        $newStatus = OrderStatus::from($validated['status']);

        $order->update([
            'status' => $newStatus,
            'completed_at' => $newStatus === OrderStatus::Completed ? now() : $order->completed_at,
        ]);

        return response()->json([
            'message' => 'Status pesanan berhasil diperbarui.',
            'data' => [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'status' => $newStatus->value,
                'status_label' => $newStatus->label(),
            ],
        ]);
    }

    /**
     * Update the payment status of an existing laundry order.
     */
    public function updatePayment(UpdatePaymentStatusRequest $request, LaundryOrder $order): JsonResponse
    {
        /** @var array{payment_status: string, payment_method?: string|null} $validated */
        $validated = $request->validated();

        $newPaymentStatus = PaymentStatus::from($validated['payment_status']);

        $updateData = [
            'payment_status' => $newPaymentStatus,
            'paid_at' => $newPaymentStatus === PaymentStatus::Paid ? now()->format('Y-m-d H:i:s') : null,
            'payment_method' => $newPaymentStatus === PaymentStatus::Paid
                ? ($validated['payment_method'] ?? 'cash')
                : null,
        ];

        $order->update($updateData);

        return response()->json([
            'message' => 'Status pembayaran berhasil diperbarui.',
            'data' => [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'payment_status' => $newPaymentStatus->value,
                'payment_status_label' => $newPaymentStatus->label(),
                'paid_at' => $order->paid_at?->format('Y-m-d H:i:s'),
                'payment_method' => $order->payment_method,
            ],
        ]);
    }
}
