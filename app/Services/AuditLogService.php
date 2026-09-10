<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class AuditLogService
{
    public function __construct(private readonly Request $request) {}

    public function log(?User $actor, string $action, Model $auditable, array $old = [], array $new = []): void
    {
        AuditLog::create([
            'user_id' => $actor?->id,
            'action' => $action,
            'auditable_type' => $auditable::class,
            'auditable_id' => $auditable->getKey(),
            'old_values' => $old,
            'new_values' => $new,
            'ip_address' => $this->request->ip(),
            'user_agent' => $this->request->userAgent(),
        ]);
    }
}
