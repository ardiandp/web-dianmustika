<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name',
    'phone',
    'instagram',
    'address',
    'height_cm',
    'weight_kg',
    'birth_count',
    'follow_ig',
])]
class Customer extends Model
{
    use HasFactory;

    public function consultations(): HasMany
    {
        return $this->hasMany(Consultation::class);
    }

    public function treatmentVisits(): HasMany
    {
        return $this->hasMany(TreatmentVisit::class);
    }

    /**
     * Normalize an Indonesian phone number to a standard form (62xxx).
     */
    public static function normalizePhone(?string $phone): ?string
    {
        if (!$phone) {
            return null;
        }

        $phone = trim($phone);
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if ($phone === '') {
            return null;
        }

        // Leading 0 -> 62
        if (str_starts_with($phone, '0')) {
            $phone = '62'.substr($phone, 1);
        } elseif (str_starts_with($phone, '8')) {
            $phone = '62'.$phone;
        }

        return $phone;
    }

    protected function casts(): array
    {
        return [
            'height_cm' => 'integer',
            'weight_kg' => 'integer',
            'follow_ig' => 'boolean',
        ];
    }

    public function scopeSearch($query, ?string $term)
    {
        if (!$term) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
                ->orWhere('phone', 'like', "%{$term}%")
                ->orWhere('instagram', 'like', "%{$term}%");
        });
    }
}
