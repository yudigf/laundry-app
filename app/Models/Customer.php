<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\CustomerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    /** @use HasFactory<CustomerFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'phone',
        'address',
    ];

    /**
     * @return HasMany<LaundryOrder, $this>
     */
    public function orders(): HasMany
    {
        return $this->hasMany(LaundryOrder::class);
    }
}
