<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

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
        'status_approval_harga',
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

    protected $appends = ['expires_at', 'is_expired'];

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

    public function scopeFilter($q, array $f = [])
    {
        $q->when(!empty($f['deals_id']), function ($qq) use ($f) {
            $qq->where('deals_id', 'like', '%' . $f['deals_id'] . '%');
        });

        $q->when(!empty($f['deal_name']), function ($qq) use ($f) {
            $qq->where('deal_name', 'like', '%' . $f['deal_name'] . '%');
        });

        $q->when(!empty($f['salper_id']), function ($qq) use ($f) {
            // Filter via Point model (salper_id)
            $qq->whereHas('points', function ($p) use ($f) {
                $p->where('salper_id', $f['salper_id']);
            });
        });

        $q->when(!empty($f['lost_reason']), function ($qq) use ($f) {
            $qq->where('lost_reason', $f['lost_reason']);
        });

        $q->when(!empty($f['receipt_number']), function ($qq) use ($f) {
            $qq->where('receipt_number', 'like', '%' . $f['receipt_number'] . '%');
        });

        $q->when(!empty($f['id_cust']), function ($qq) use ($f) {
            $qq->where('id_cust', 'like', '%' . $f['id_cust'] . '%');
        });

        // Date ranges (created_date)
        $q->when(!empty($f['created_date_from']), function ($qq) use ($f) {
            $qq->whereDate('created_date', '>=', $f['created_date_from']);
        });
        $q->when(!empty($f['created_date_to']), function ($qq) use ($f) {
            $qq->whereDate('created_date', '<=', $f['created_date_to']);
        });

        // Date ranges (closed_date)
        $q->when(!empty($f['closed_date_from']), function ($qq) use ($f) {
            $qq->whereDate('closed_date', '>=', $f['closed_date_from']);
        });
        $q->when(!empty($f['closed_date_to']), function ($qq) use ($f) {
            $qq->whereDate('closed_date', '<=', $f['closed_date_to']);
        });

        return $q;
    }

    /**
     * Deals that are waiting for "harga khusus" approval.
     * Expecting status_approval_harga = REQUEST_HARGA_KHUSUS
     */
    public function scopeNeedHargaApproval($q)
    {
        return $q->where('status_approval_harga', 'REQUEST_HARGA_KHUSUS');
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

    public function getExpiresAtAttribute()
    {
        // map stage → grace period (days)
        $stage = strtolower((string) $this->stage);
        $daysMap = [
            'mapping' => 14,
            'visit' => 14,
            'quotation' => 30,
        ];

        if (!isset($daysMap[$stage]) || !$this->created_date) {
            return null;
        }

        // created_date + N days (no timezone shift here)
        return $this->created_date->copy()->addDays($daysMap[$stage]);
    }

    public function getIsExpiredAttribute()
    {
        // won/lost are never “expired”
        $stage = strtolower((string) $this->stage);
        if (in_array($stage, ['won', 'lost'], true)) {
            return false;
        }

        $expiresAt = $this->expires_at;
        if (!$expiresAt) {
            return false;
        }

        // compare using Asia/Jakarta to match your locale
        return Carbon::now('Asia/Jakarta')->greaterThan($expiresAt);
    }
}
