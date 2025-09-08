<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DealReport extends Model
{
    protected $table = 'deals_reports';

    public $timestamps = false;

    protected $fillable = [
        'deals_id',
        'stage',
        'created_date',
        'closed_date', 
        'updated_by',
        'updated_at',
    ];

    protected $casts = [
        'updated_at' => 'datetime',
        'created_date' => 'date',
        'closed_date'  => 'date',
    ];

    public function deal()
    {
        return $this->belongsTo(Deal::class, 'deals_id', 'deals_id');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
