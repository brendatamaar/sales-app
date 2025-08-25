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

    public function setStageAttribute($value): void
    {
        $this->attributes['stage'] = is_string($value) ? strtolower(trim($value)) : $value;
    }

    public function points()
    {
        return $this->hasMany(Point::class, 'stage', 'stage');
    }

    public function scopeForStage($query, string $stage)
    {
        return $query->where('stage', strtolower(trim($stage)));
    }
}
