<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $table = 'stores';
    protected $primaryKey = 'store_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'store_name',
        'store_address',
        'region',
        'no_rek_store',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'store_id');
    }

    public function salpers()
    {
        return $this->hasMany(Salper::class, 'store_id');
    }

    public function dataCustomers()
    {
        return $this->hasMany(DataCustomer::class, 'store_id');
    }

    public function deals()
    {
        return $this->hasMany(Deal::class, 'store_id');
    }
}
