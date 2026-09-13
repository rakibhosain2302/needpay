<?php

namespace Tests\Feature\Admin;

use App\Models\Driver;
use App\Models\DriverDocument;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesUsers;
use Tests\TestCase;

class DriverVerificationTest extends TestCase
{
    use CreatesUsers, RefreshDatabase;

    private function approveRequiredDocuments(Driver $driver): void
    {
        foreach (['driving_license', 'nid', 'vehicle_registration'] as $type) {
            DriverDocument::factory()->for($driver)->approved()->create(['document_type' => $type]);
        }
    }

    public function test_new_driver_starts_pending(): void
    {
        $driver = $this->driverUser();

        $this->assertSame('pending', $driver->verification_status->value);
    }

    public function test_admin_can_approve_driver_with_required_documents(): void
    {
        $driver = $this->driverUser();
        $this->approveRequiredDocuments($driver);
        $admin = $this->adminUser(['drivers.verify']);

        $this->actingAs($admin, 'sanctum')
            ->postJson("/api/v1/admin/drivers/{$driver->id}/approve")
            ->assertOk()
            ->assertJsonPath('data.verification_status', 'approved');
    }

    public function test_admin_cannot_approve_driver_with_missing_documents(): void
    {
        $driver = $this->driverUser();
        $admin = $this->adminUser(['drivers.verify']);

        $this->actingAs($admin, 'sanctum')
            ->postJson("/api/v1/admin/drivers/{$driver->id}/approve")
            ->assertUnprocessable();

        $this->assertSame('pending', $driver->fresh()->verification_status->value);
    }

    public function test_admin_can_reject_driver(): void
    {
        $driver = $this->driverUser();
        $admin = $this->adminUser(['drivers.verify']);

        $this->actingAs($admin, 'sanctum')
            ->postJson("/api/v1/admin/drivers/{$driver->id}/reject", ['rejection_reason' => 'Incomplete info'])
            ->assertOk()
            ->assertJsonPath('data.verification_status', 'rejected');
    }

    public function test_rejection_reason_is_required(): void
    {
        $driver = $this->driverUser();
        $admin = $this->adminUser(['drivers.verify']);

        $this->actingAs($admin, 'sanctum')
            ->postJson("/api/v1/admin/drivers/{$driver->id}/reject", [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('rejection_reason');
    }

    public function test_admin_can_suspend_driver(): void
    {
        $driver = $this->driverUser(['verification_status' => 'approved', 'is_online' => true, 'availability_status' => 'online']);
        $admin = $this->adminUser(['drivers.suspend']);

        $this->actingAs($admin, 'sanctum')
            ->postJson("/api/v1/admin/drivers/{$driver->id}/suspend")
            ->assertOk()
            ->assertJsonPath('data.verification_status', 'suspended');

        $driver->refresh();
        $this->assertFalse($driver->is_online);
        $this->assertSame('offline', $driver->availability_status->value);
    }

    public function test_passenger_cannot_access_admin_driver_endpoints(): void
    {
        $passenger = User::factory()->passenger()->create();

        $this->actingAs($passenger, 'sanctum')
            ->getJson('/api/v1/admin/drivers')
            ->assertForbidden();
    }
}
