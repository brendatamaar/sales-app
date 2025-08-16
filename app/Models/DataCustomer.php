<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataCustomer extends Model
{
    use HasFactory;

    protected $table = 'data_customers';
    protected $primaryKey = 'id_cust';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'cust_name',
        'cust_address',
        'no_telp_cust',
        'longitude',
        'latitude',
        'store_id',
    ];

    protected $casts = [
        'longitude' => 'decimal:7',
        'latitude' => 'decimal:7',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function deals()
    {
        return $this->hasMany(Deal::class, 'id_cust');
    }
}
