<?php

namespace App\Http\Controllers\Api\V1\Driver;

use App\Http\Concerns\ApiResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\Driver\StoreDriverDocumentRequest;
use App\Http\Requests\Driver\UpdateDriverDocumentRequest;
use App\Enums\DocumentStatus;
use App\Http\Resources\DriverDocumentResource;
use App\Models\DriverDocument;
use App\Services\DriverDocumentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DriverDocumentController extends Controller
{
    use ApiResponses;

    public function __construct(private readonly DriverDocumentService $documentService) {}

    public function index(Request $request): JsonResponse
    {
        $driver = $request->user()->driver()->firstOrFail();

        $documents = $driver->documents()->latest()->get();

        return $this->success(DriverDocumentResource::collection($documents), 'Documents retrieved successfully.');
    }

    public function store(StoreDriverDocumentRequest $request): JsonResponse
    {
        $driver = $request->user()->driver()->firstOrFail();

        $document = $this->documentService->submit($driver, $request->validated(), $request->file('file'));

        return $this->success(new DriverDocumentResource($document), 'Document submitted successfully.', 201);
    }

    public function update(UpdateDriverDocumentRequest $request, DriverDocument $document): JsonResponse
    {
        $this->authorize('update', $document);

        $document = $this->documentService->resubmit($document, $request->validated(), $request->file('file'));

        return $this->success(new DriverDocumentResource($document), 'Document resubmitted successfully.');
    }

    public function destroy(DriverDocument $document): JsonResponse
    {
        $this->authorize('update', $document);

        if ($document->status === DocumentStatus::Approved) {
            throw ValidationException::withMessages([
                'document' => ['An approved document cannot be deleted.'],
            ]);
        }

        $document->delete();

        return $this->success(null, 'Document deleted successfully.');
    }
}
