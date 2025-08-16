<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    use HasFactory;

    protected $table = 'deals';
    protected $primaryKey = 'deals_id';
    public $incrementing = false;         // varchar PK
    protected $keyType = 'string';

    protected $fillable = [
        'deals_id',
        'deal_name',
        'stage',
        'deal_size',
        'created_date',
        'closed_date',
        'store_id',
        'store_name',
        'no_rek_store',
        'email',
        'salper_id_mapping',
        'alamat_lengkap',
        'notes',
        'phto_upload',
        'sales_id_visit',
        'id_cust',
        'cust_name',
        'no_telp_cust',
        'payment_term',
        'quotation_exp_date',
        'sales_id_quotation',
        'quotation_upload',
        'sales_id_won',
        'receipt_number',
        'receipt_upload',
        'lost_reason',
        'item_no',
        'item_name',
        'item_qty',
        'fix_price',
        'total_price',
    ];

    protected $casts = [
        'deal_size' => 'decimal:2',
        'created_date' => 'date',
        'closed_date' => 'date',
        'quotation_exp_date' => 'date',
        'phto_upload' => 'array',
        'quotation_upload' => 'array',
        'receipt_upload' => 'array',
        'item_qty' => 'integer',
        'fix_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function salperMapping()
    {
        return $this->belongsTo(Salper::class, 'salper_id_mapping');
    }

    public function customer()
    {
        return $this->belongsTo(DataCustomer::class, 'id_cust');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_no');
    }

    public function points()
    {
        return $this->hasMany(Point::class, 'deals_id', 'deals_id');
    }
}
