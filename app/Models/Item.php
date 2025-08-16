<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $table = 'items';
    protected $primaryKey = 'item_no';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'item_name',
        'uom',
        'price',
        'disc',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'disc' => 'decimal:2',
    ];

    public function deals()
    {
        return $this->hasMany(Deal::class, 'item_no');
    }
}
