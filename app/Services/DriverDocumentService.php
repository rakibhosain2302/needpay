<?php

namespace App\Services;

use App\Enums\DocumentStatus;
use App\Models\Driver;
use App\Models\DriverDocument;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DriverDocumentService
{
    public function __construct(private readonly AuditLogService $auditLog) {}

    public function submit(Driver $driver, array $data, UploadedFile $file): DriverDocument
    {
        return DB::transaction(function () use ($driver, $data, $file) {
            $path = $file->store("driver-documents/{$driver->id}", 'local');

            return DriverDocument::create([
                'driver_id' => $driver->id,
                'document_type' => $data['document_type'],
                'document_number' => $data['document_number'] ?? null,
                'file_path' => $path,
                'issued_at' => $data['issued_at'] ?? null,
                'expires_at' => $data['expires_at'] ?? null,
                'status' => DocumentStatus::Pending,
            ]);
        });
    }

    public function resubmit(DriverDocument $document, array $data, ?UploadedFile $file): DriverDocument
    {
        return DB::transaction(function () use ($document, $data, $file) {
            $updates = array_filter([
                'document_number' => $data['document_number'] ?? null,
                'issued_at' => $data['issued_at'] ?? null,
                'expires_at' => $data['expires_at'] ?? null,
            ], fn ($value) => $value !== null);

            if ($file) {
                Storage::disk('local')->delete($document->file_path);
                $updates['file_path'] = $file->store("driver-documents/{$document->driver_id}", 'local');
            }

            $updates['status'] = DocumentStatus::Pending;
            $updates['rejection_reason'] = null;
            $updates['verified_by'] = null;
            $updates['verified_at'] = null;

            $document->update($updates);

            return $document->fresh();
        });
    }

    public function approve(DriverDocument $document, User $admin): DriverDocument
    {
        return DB::transaction(function () use ($document, $admin) {
            $old = $document->only(['status', 'verified_by', 'verified_at', 'rejection_reason']);

            $document->forceFill([
                'status' => DocumentStatus::Approved,
                'verified_by' => $admin->id,
                'verified_at' => now(),
                'rejection_reason' => null,
            ])->save();

            $this->auditLog->log($admin, 'driver_document.approved', $document, $old, $document->only([
                'status', 'verified_by', 'verified_at', 'rejection_reason',
            ]));

            return $document;
        });
    }

    public function reject(DriverDocument $document, User $admin, string $reason): DriverDocument
    {
        return DB::transaction(function () use ($document, $admin, $reason) {
            $old = $document->only(['status', 'verified_by', 'verified_at', 'rejection_reason']);

            $document->forceFill([
                'status' => DocumentStatus::Rejected,
                'verified_by' => $admin->id,
                'verified_at' => now(),
                'rejection_reason' => $reason,
            ])->save();

            $this->auditLog->log($admin, 'driver_document.rejected', $document, $old, $document->only([
                'status', 'verified_by', 'verified_at', 'rejection_reason',
            ]));

            return $document;
        });
    }
}
