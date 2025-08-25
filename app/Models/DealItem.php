<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DealItem extends Model
{
    use HasFactory;

    protected $table = 'deals_items';

    protected $fillable = [
        'deals_id',
        'item_no',
        'quantity',
        'unit_price',
        'discount_percent',
        'line_total',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'line_total' => 'decimal:2',
    ];

    // Relationships
    public function deal()
    {
        return $this->belongsTo(Deal::class, 'deals_id', 'deals_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_no', 'item_no');
    }

    // Calculate line total automatically
    public function calculateLineTotal()
    {
        $subtotal = $this->quantity * $this->unit_price;
        $discount = ($this->discount_percent / 100) * $subtotal;
        return $subtotal - $discount;
    }

    // Auto-calculate line total when saving
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($dealItem) {
            if ($dealItem->quantity && $dealItem->unit_price) {
                $dealItem->line_total = $dealItem->calculateLineTotal();
            }
        });
    }

    // Scope for getting items by deal
    public function scopeForDeal($query, $dealId)
    {
        return $query->where('deals_id', $dealId);
    }
}