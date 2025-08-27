<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationItem extends Model
{
    protected $fillable = [
        'quotation_id',
        'row_no',
        'item_no',
        'item_name',
        'uom',
        'quantity',
        'unit_price',
        'discount_percent',
        'line_total',
    ];
}