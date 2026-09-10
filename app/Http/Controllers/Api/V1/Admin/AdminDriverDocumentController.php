<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Concerns\ApiResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RejectDriverDocumentRequest;
use App\Http\Resources\DriverDocumentResource;
use App\Models\DriverDocument;
use App\Services\DriverDocumentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminDriverDocumentController extends Controller
{
    use ApiResponses;

    public function __construct(private readonly DriverDocumentService $documentService) {}

    public function index(Request $request): JsonResponse
    {
        $query = DriverDocument::query()->with('driver.user');

        if ($driverId = $request->query('driver_id')) {
            $query->where('driver_id', $driverId);
        }

        if ($type = $request->query('document_type')) {
            $query->where('document_type', $type);
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        if ($request->boolean('expired')) {
            $query->whereNotNull('expires_at')->where('expires_at', '<', now());
        }

        if ($from = $request->query('submitted_from')) {
            $query->whereDate('created_at', '>=', $from);
        }

        if ($to = $request->query('submitted_to')) {
            $query->whereDate('created_at', '<=', $to);
        }

        $documents = $query->latest()->paginate($request->integer('per_page', 15));

        return $this->success(DriverDocumentResource::collection($documents)->response()->getData(true), 'Documents retrieved successfully.');
    }

    public function show(DriverDocument $document): JsonResponse
    {
        return $this->success(new DriverDocumentResource($document->load('driver.user')), 'Document retrieved successfully.');
    }

    public function file(DriverDocument $document): StreamedResponse
    {
        abort_unless(Storage::disk('local')->exists($document->file_path), 404, 'File not found.');

        return Storage::disk('local')->download($document->file_path);
    }

    public function approve(Request $request, DriverDocument $document): JsonResponse
    {
        $document = $this->documentService->approve($document, $request->user());

        return $this->success(new DriverDocumentResource($document), 'Document approved successfully.');
    }

    public function reject(RejectDriverDocumentRequest $request, DriverDocument $document): JsonResponse
    {
        $document = $this->documentService->reject($document, $request->user(), $request->validated('rejection_reason'));

        return $this->success(new DriverDocumentResource($document), 'Document rejected successfully.');
    }
}
