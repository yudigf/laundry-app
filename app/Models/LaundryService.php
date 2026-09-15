<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\LaundryServiceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LaundryService extends Model
{
    /** @use HasFactory<LaundryServiceFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'description',
        'price_per_unit',
        'unit',
        'estimated_hours',
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price_per_unit' => 'integer',
            'estimated_hours' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * @return HasMany<OrderItem, $this>
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
