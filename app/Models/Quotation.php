<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    protected $fillable = [
        'quotation_no',
        'deals_id',
        'created_date',
        'expired_date',
        'valid_days',
        'store_id',
        'store_name',
        'customer_name',
        'no_rek_store',
        'payment_term',
        'grand_total',
        'meta',
    ];
    protected $casts = [
        'created_date' => 'date',
        'expired_date' => 'date',
        'grand_total' => 'decimal:2',
        'meta' => 'array',
    ];

    public function items()
    {
        return $this->hasMany(QuotationItem::class);
    }
}