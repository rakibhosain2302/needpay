<?php

namespace App\Models;

use App\Enums\DocumentStatus;
use App\Enums\DocumentType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'driver_id', 'document_type', 'document_number', 'file_path',
    'issued_at', 'expires_at', 'status', 'verified_by', 'verified_at', 'rejection_reason',
])]
class DriverDocument extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'issued_at' => 'date',
            'expires_at' => 'date',
            'verified_at' => 'datetime',
            'document_type' => DocumentType::class,
            'status' => DocumentStatus::class,
        ];
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }

    public function isValid(): bool
    {
        return $this->status === DocumentStatus::Approved && ! $this->isExpired();
    }
}
