<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['user_id', 'customer_id', 'date', 'total', 'igv'])]
class Sale extends Model
{
    public const IGV = 0.18;

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(DetailSale::class);
    }

    public function calculateTotal(): float
    {
        $total = round((float) $this->details()->sum('subtotal'), 2);

        $this->update([
            'total' => $total,
            'igv'   => round($total - $total / (1 + self::IGV), 2),
        ]);

        return $total;
    }
}
