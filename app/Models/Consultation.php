<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'customer_id',
    'flow_name',
    'status',
    'answers',
    'submitted_at',
    'consent_at',
    'admin_notes',
])]
class Consultation extends Model
{
    use HasFactory;

    public const STATUS = [
        'baru' => 'Baru',
        'dihubungi' => 'Dihubungi',
        'menunggu_konfirmasi' => 'Menunggu Konfirmasi',
        'booking' => 'Booking',
        'treatment_berlangsung' => 'Treatment Berlangsung',
        'selesai' => 'Selesai',
        'follow_up' => 'Follow Up',
        'dibatalkan' => 'Dibatalkan',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function treatmentVisits(): HasMany
    {
        return $this->hasMany(TreatmentVisit::class);
    }

    public function statusLabel(): string
    {
        return self::STATUS[$this->status] ?? $this->status;
    }

    /**
     * Mengambil daftar langkah (step) dari config untuk flow ini.
     */
    public function stepFields(): array
    {
        return config('consultation.steps', []);
    }

    /**
     * Mengambil label untuk sebuah key jawaban dari config.
     */
    public function answerLabel(string $key): ?string
    {
        foreach ($this->stepFields() as $step) {
            foreach ($step['fields'] as $field) {
                if ($field['key'] === $key) {
                    return $field['label'];
                }
                if (! empty($field['others_textarea']) && ($field['others_textarea']['key'] ?? null) === $key) {
                    return $field['others_textarea']['label'] ?? $field['label'].' (lainnya)';
                }
            }
        }
        return null;
    }

    /**
     * Mengubah nilai jawaban menjadi label yang ramah untuk ditampilkan.
     */
    public function answerValue(string $key, $value)
    {
        if ($value === null || $value === '' ) return null;
        if (is_array($value) && count($value) === 0) return null;

        $field = null;
        foreach ($this->stepFields() as $step) {
            foreach ($step['fields'] as $f) {
                if ($f['key'] === $key) { $field = $f; break 2; }
            }
        }

        if (! $field) {
            return is_array($value) ? implode(', ', $value) : $value;
        }

        $options = $field['options'] ?? [];

        $resolve = function ($v) use ($options) {
            foreach ($options as $o) {
                if (($o['value'] ?? null) == $v) return $o['label'];
            }
            return $v;
        };

        if (is_array($value)) {
            return implode(', ', array_map($resolve, $value));
        }

        return $resolve($value);
    }

    protected function casts(): array
    {
        return [
            'answers' => 'array',
            'submitted_at' => 'datetime',
            'consent_at' => 'datetime',
        ];
    }

    public function scopeSearch($query, ?string $term)
    {
        if (!$term) {
            return $query;
        }

        $like = "%{$term}%";

        return $query->where(function ($q) use ($like) {
            $q->whereHas('customer', function ($c) use ($like) {
                $c->where('name', 'like', $like)
                    ->orWhere('phone', 'like', $like)
                    ->orWhere('instagram', 'like', $like);
            });
        });
    }
}
