<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $table = 'items';
    protected $primaryKey = 'item_no';

    protected $fillable = [
        'item_name',
        'category',
        'uom',
        'price',
        'disc',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'disc' => 'decimal:2',
    ];

    // Relationship with deal items
    public function dealItems()
    {
        return $this->hasMany(DealItem::class, 'item_no', 'item_no');
    }

    // Many-to-many relationship with deals through deals_items
    public function deals()
    {
        return $this->belongsToMany(Deal::class, 'deals_items', 'item_no', 'deals_id')
            ->withPivot(['quantity', 'unit_price', 'discount_percent', 'line_total', 'notes'])
            ->withTimestamps();
    }

    // Get formatted price
    public function getFormattedPrice()
    {
        return number_format($this->price, 2);
    }

    // Get price after discount
    public function getPriceAfterDiscount()
    {
        $discount = ($this->disc / 100) * $this->price;
        return $this->price - $discount;
    }

    // Scope for search
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('item_name', 'like', "%{$search}%")
                ->orWhere('category', 'like', "%{$search}%");
        });
    }

    // Scope for category filter
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}