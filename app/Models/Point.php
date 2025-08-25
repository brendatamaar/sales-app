<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    use HasFactory;

    protected $table = 'points';
    protected $primaryKey = 'point_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'deals_id',
        'stage',
        'salper_id',
        'total_points',
    ];

    protected $casts = [
        'total_points' => 'integer',
    ];

    public function deal()
    {
        return $this->belongsTo(Deal::class, 'deals_id', 'deals_id');
    }

    public function salper()
    {
        return $this->belongsTo(Salper::class, 'salper_id');
    }
}
