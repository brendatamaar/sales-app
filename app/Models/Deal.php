<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    use HasFactory;

    protected $table = 'deals';
    protected $primaryKey = 'deals_id';
    public $incrementing = false;         // varchar PK
    protected $keyType = 'string';

    protected $fillable = [
        'deals_id',
        'deal_name',
        'stage',
        'deal_size',
        'created_date',
        'closed_date',
        'store_id',
        'store_name',
        'no_rek_store',
        'email',
        'alamat_lengkap',
        'notes',
        'photo_upload',
        'id_cust',
        'cust_name',
        'no_telp_cust',
        'payment_term',
        'quotation_exp_date',
        'quotation_upload',
        'receipt_number',
        'receipt_upload',
        'lost_reason',
    ];

    protected $casts = [
        'deal_size' => 'decimal:2',
        'created_date' => 'date',
        'closed_date' => 'date',
        'quotation_exp_date' => 'date',
        'photo_upload' => 'array',
        'quotation_upload' => 'array',
        'receipt_upload' => 'array',
    ];

    public function getRouteKeyName()
    {
        return 'deals_id';
    }

    // Relationships
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function customer()
    {
        return $this->belongsTo(DataCustomer::class, 'id_cust');
    }

    public function points()
    {
        return $this->hasMany(Point::class, 'deals_id', 'deals_id');
    }

    public function dealItems()
    {
        return $this->hasMany(DealItem::class, 'deals_id', 'deals_id');
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'deals_items', 'deals_id', 'item_no')
            ->withPivot(['quantity', 'unit_price', 'discount_percent', 'line_total', 'notes'])
            ->withTimestamps();
    }

    public function calculateTotalValue()
    {
        return $this->dealItems()->sum('line_total');
    }

    public function getFormattedDealSize()
    {
        $total = $this->deal_size ?? $this->calculateTotalValue();
        return number_format($total, 2);
    }

    // Scopes
    public function scopeByStage($query, $stage)
    {
        return $query->where('stage', $stage);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('deal_name', 'like', "%{$search}%")
                ->orWhere('deals_id', 'like', "%{$search}%")
                ->orWhere('stage', 'like', "%{$search}%");
        });
    }

    public function getStageDaysAttribute()
    {
        $start = $this->created_date ?: $this->created_at;
        $end = $this->closed_date ?: Carbon::now();

        if (!$start) {
            return null;
        }

        return $end->diffInDays($start);
    }

    public function getStageDaysLabelAttribute()
    {
        $days = $this->stage_days;
        return $days === null ? null : ($days . ' hari di stage ini');
    }
}
