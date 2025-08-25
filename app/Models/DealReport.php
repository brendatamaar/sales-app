<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DealReport extends Model
{
    protected $table = 'deals_reports';

    // we only have updated_at (no created_at), so disable default timestamps
    public $timestamps = false;

    protected $fillable = [
        'deals_id',
        'stage',
        'updated_by',
        'updated_at',
    ];

    protected $casts = [
        'updated_at' => 'datetime',
    ];

    public function deal()
    {
        return $this->belongsTo(Deal::class, 'deals_id', 'deals_id');
    }

    public function updater()
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }
}
