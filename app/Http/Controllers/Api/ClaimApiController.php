<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ClaimService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ClaimApiController extends Controller
{
    public function __construct(protected ClaimService $claimService) {}

    /**
     * POST /api/claim  (legacy)
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate(['textile_id' => 'required|integer|exists:textiles,id']);

        $result = $this->claimService->claim($request->textile_id, auth()->id() ?? auth('api')->id());

        if (!$result['success']) {
            return response()->json(['success' => false, 'message' => $result['message']], 422);
        }

        $textile = $result['textile'];
        $owner   = $textile->owner;

        return response()->json([
            'success'  => true,
            'message'  => $result['message'],
            'whatsapp' => [
                'number'  => $owner?->whatsapp,
                'name'    => $owner?->name,
                'address' => $textile->address,
                'title'   => $textile->title,
                'url'     => 'https://wa.me/' . preg_replace('/\D/', '', $owner?->whatsapp ?? '') .
                    '?text=' . urlencode(
                        "Halo, saya dari UpcycleMatch.\n\nSaya telah mengklaim limbah kain:\n[{$textile->title}]\n\nSaya ingin melakukan koordinasi penjemputan.\n\nTerima kasih."
                    ),
            ],
        ]);
    }

    /**
     * POST /api/v1/textiles/{textile}/claim  (v1 JWT, route-model-binding)
     */
    public function claimViaRoute(\App\Models\Textile $textile): JsonResponse
    {
        $userId = auth('api')->id();
        $result = $this->claimService->claim($textile->id, $userId);

        if (!$result['success']) {
            return response()->json(['success' => false, 'message' => $result['message']], 422);
        }

        $t     = $result['textile'];
        $owner = $t->owner;

        return response()->json([
            'success'  => true,
            'message'  => $result['message'],
            'whatsapp' => [
                'number'  => $owner?->whatsapp,
                'name'    => $owner?->name,
                'url'     => 'https://wa.me/' . preg_replace('/\D/', '', $owner?->whatsapp ?? ''),
            ],
        ]);
    }

    /**
     * POST /api/start-production
     */
    public function startProduction(Request $request): JsonResponse
    {
        $request->validate(['textile_id' => 'required|integer|exists:textiles,id']);

        $result = app(ClaimService::class)->updateStatus($request->textile_id, auth()->id(), 'processing');

        return response()->json($result, $result['success'] ? 200 : 422);
    }

    /**
     * POST /api/finish-production
     */
    public function finishProduction(Request $request): JsonResponse
    {
        $request->validate(['textile_id' => 'required|integer|exists:textiles,id']);

        $result = app(ClaimService::class)->updateStatus($request->textile_id, auth()->id(), 'completed');

        return response()->json($result, $result['success'] ? 200 : 422);
    }
}
