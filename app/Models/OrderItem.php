<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\OrderItemFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    /** @use HasFactory<OrderItemFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'laundry_order_id',
        'laundry_service_id',
        'quantity',
        'unit_price',
        'subtotal',
        'notes',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'quantity' => 'float',
            'unit_price' => 'integer',
            'subtotal' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<LaundryOrder, $this>
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(LaundryOrder::class, 'laundry_order_id');
    }

    /**
     * @return BelongsTo<LaundryService, $this>
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(LaundryService::class, 'laundry_service_id');
    }
}
