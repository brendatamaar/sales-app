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
        'salper_id_mapping',
        'bobot_mapping_id',
        'bobot_mapping',
        'salper_id_visit',
        'bobot_id_visit',
        'bobot_visit',
        'salper_id_quotation',
        'bobot_id_quotation',
        'bobot_quotation',
        'salper_id_won',
        'bobot_id_won',
        'bobot_won',
        'total_point',
    ];

    protected $casts = [
        'bobot_mapping' => 'integer',
        'bobot_visit' => 'integer',
        'bobot_quotation' => 'integer',
        'bobot_won' => 'integer',
        'total_point' => 'integer',
    ];

    public function deal()
    {
        return $this->belongsTo(Deal::class, 'deals_id', 'deals_id');
    }

    public function salperMapping()
    {
        return $this->belongsTo(Salper::class, 'salper_id_mapping');
    }
    public function salperVisit()
    {
        return $this->belongsTo(Salper::class, 'salper_id_visit');
    }
    public function salperQuotation()
    {
        return $this->belongsTo(Salper::class, 'salper_id_quotation');
    }
    public function salperWon()
    {
        return $this->belongsTo(Salper::class, 'salper_id_won');
    }

    public function bobotMapping()
    {
        return $this->belongsTo(Bobot::class, 'bobot_mapping_id');
    }
    public function bobotVisit()
    {
        return $this->belongsTo(Bobot::class, 'bobot_id_visit');
    }
    public function bobotQuotation()
    {
        return $this->belongsTo(Bobot::class, 'bobot_id_quotation');
    }
    public function bobotWon()
    {
        return $this->belongsTo(Bobot::class, 'bobot_id_won');
    }
}
