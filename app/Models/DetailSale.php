<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['sale_id', 'medicine_id', 'amount', 'unit_price', 'subtotal'])]
class DetailSale extends Model
{
    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function medicine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class);
    }

    public function calculateSubtotal(): float
    {
        return round($this->amount * $this->unit_price, 2);
    }

    public function validateStock(): bool
    {
        return $this->medicine->stock >= $this->amount;
    }
}
