<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bobot extends Model
{
    use HasFactory;

    protected $table = 'bobots';
    protected $primaryKey = 'bobot_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'stage',
        'point',
    ];

    protected $casts = [
        'point' => 'integer',
    ];

    public function pointsAsMapping()
    {
        return $this->hasMany(Point::class, 'bobot_mapping_id');
    }
    public function pointsAsVisit()
    {
        return $this->hasMany(Point::class, 'bobot_id_visit');
    }
    public function pointsAsQuotation()
    {
        return $this->hasMany(Point::class, 'bobot_id_quotation');
    }
    public function pointsAsWon()
    {
        return $this->hasMany(Point::class, 'bobot_id_won');
    }
}
