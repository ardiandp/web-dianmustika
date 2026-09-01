<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'customer_id',
    'consultation_id',
    'service_id',
    'visit_date',
    'status',
    'therapist_notes',
    'post_treatment_notes',
    'recommendation',
    'next_follow_up_at',
])]
class TreatmentVisit extends Model
{
    use HasFactory;

    public const STATUS = [
        'dijadwalkan' => 'Dijadwalkan',
        'selesai' => 'Selesai',
        'dibatalkan' => 'Dibatalkan',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function consultation(): BelongsTo
    {
        return $this->belongsTo(Consultation::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function statusLabel(): string
    {
        return self::STATUS[$this->status] ?? $this->status;
    }

    protected function casts(): array
    {
        return [
            'visit_date' => 'date',
            'next_follow_up_at' => 'date',
        ];
    }
}
