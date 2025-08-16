<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salper extends Model
{
    use HasFactory;

    protected $table = 'salpers';
    protected $primaryKey = 'salper_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'salper_name',
        'store_id',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    // Deals where this salper is mapped
    public function dealsMapping()
    {
        return $this->hasMany(Deal::class, 'salper_id_mapping');
    }

    // Points relations by role
    public function pointsAsMapping()
    {
        return $this->hasMany(Point::class, 'salper_id_mapping');
    }
    public function pointsAsVisit()
    {
        return $this->hasMany(Point::class, 'salper_id_visit');
    }
    public function pointsAsQuotation()
    {
        return $this->hasMany(Point::class, 'salper_id_quotation');
    }
    public function pointsAsWon()
    {
        return $this->hasMany(Point::class, 'salper_id_won');
    }
}
