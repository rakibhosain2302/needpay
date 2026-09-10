<?php

namespace Tests\Feature\Driver;

use App\Models\DriverDocument;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Concerns\CreatesUsers;
use Tests\TestCase;

class DriverDocumentTest extends TestCase
{
    use CreatesUsers, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
    }

    public function test_driver_can_submit_document(): void
    {
        $driver = $this->driverUser();

        $response = $this->actingAs($driver->user, 'sanctum')->postJson('/api/v1/driver/documents', [
            'document_type' => 'nid',
            'document_number' => 'NID-12345',
            'file' => UploadedFile::fake()->create('nid.pdf', 200, 'application/pdf'),
        ]);

        $response->assertCreated()->assertJsonPath('data.status', 'pending');
        $this->assertDatabaseHas('driver_documents', ['driver_id' => $driver->id, 'document_type' => 'nid']);
    }

    public function test_driver_can_view_own_documents(): void
    {
        $driver = $this->driverUser();
        DriverDocument::factory()->for($driver)->create();

        $this->actingAs($driver->user, 'sanctum')
            ->getJson('/api/v1/driver/documents')
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }

    public function test_driver_cannot_access_another_drivers_document(): void
    {
        $driverA = $this->driverUser();
        $driverB = $this->driverUser();
        $document = DriverDocument::factory()->for($driverB)->create();

        $this->actingAs($driverA->user, 'sanctum')
            ->putJson("/api/v1/driver/documents/{$document->id}", ['document_number' => 'HACKED'])
            ->assertForbidden();
    }

    public function test_invalid_file_type_rejected(): void
    {
        $driver = $this->driverUser();

        $response = $this->actingAs($driver->user, 'sanctum')->postJson('/api/v1/driver/documents', [
            'document_type' => 'nid',
            'file' => UploadedFile::fake()->create('malware.exe', 100, 'application/x-msdownload'),
        ]);

        $response->assertUnprocessable()->assertJsonValidationErrors('file');
    }

    public function test_invalid_document_type_rejected(): void
    {
        $driver = $this->driverUser();

        $response = $this->actingAs($driver->user, 'sanctum')->postJson('/api/v1/driver/documents', [
            'document_type' => 'passport',
            'file' => UploadedFile::fake()->create('doc.pdf', 100, 'application/pdf'),
        ]);

        $response->assertUnprocessable()->assertJsonValidationErrors('document_type');
    }

    public function test_admin_can_approve_document(): void
    {
        $driver = $this->driverUser();
        $document = DriverDocument::factory()->for($driver)->create();
        $admin = $this->adminUser(['drivers.verify']);

        $this->actingAs($admin, 'sanctum')
            ->postJson("/api/v1/admin/driver-documents/{$document->id}/approve")
            ->assertOk()
            ->assertJsonPath('data.status', 'approved');

        $this->assertDatabaseHas('driver_documents', ['id' => $document->id, 'status' => 'approved', 'verified_by' => $admin->id]);
    }

    public function test_admin_can_reject_document_with_reason(): void
    {
        $driver = $this->driverUser();
        $document = DriverDocument::factory()->for($driver)->create();
        $admin = $this->adminUser(['drivers.verify']);

        $this->actingAs($admin, 'sanctum')
            ->postJson("/api/v1/admin/driver-documents/{$document->id}/reject", ['rejection_reason' => 'Blurry image'])
            ->assertOk()
            ->assertJsonPath('data.status', 'rejected');

        $this->assertDatabaseHas('driver_documents', ['id' => $document->id, 'status' => 'rejected', 'rejection_reason' => 'Blurry image']);
    }

    public function test_rejection_reason_is_required(): void
    {
        $driver = $this->driverUser();
        $document = DriverDocument::factory()->for($driver)->create();
        $admin = $this->adminUser(['drivers.verify']);

        $this->actingAs($admin, 'sanctum')
            ->postJson("/api/v1/admin/driver-documents/{$document->id}/reject", [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('rejection_reason');
    }

    public function test_rejected_document_can_be_resubmitted(): void
    {
        $driver = $this->driverUser();
        $document = DriverDocument::factory()->for($driver)->create([
            'status' => 'rejected',
            'rejection_reason' => 'Bad photo',
        ]);

        $response = $this->actingAs($driver->user, 'sanctum')->putJson("/api/v1/driver/documents/{$document->id}", [
            'file' => UploadedFile::fake()->create('nid-new.pdf', 150, 'application/pdf'),
        ]);

        $response->assertOk()
            ->assertJsonPath('data.status', 'pending')
            ->assertJsonPath('data.rejection_reason', null);
    }
}
