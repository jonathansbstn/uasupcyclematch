<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Textile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use OpenApi\Attributes as OA;

/**
 * TextileApiController
 *
 * Endpoint untuk data limbah kain (textile).
 * Semua endpoint dilindungi JWT.
 */
class TextileApiController extends Controller
{
    /**
     * GET /api/v1/textiles
     * Ambil semua limbah yang tersedia (publik / JWT).
     */
    #[OA\Get(
        path: "/api/v1/textiles",
        summary: "Daftar Semua Limbah Kain",
        description: "Mengambil daftar limbah kain. Endpoint ini bisa diakses secara publik.",
        tags: ["Textiles"],
        parameters: [
            new OA\Parameter(name: "per_page", in: "query", required: false, description: "Jumlah per halaman", schema: new OA\Schema(type: "integer", default: 15))
        ],
        responses: [
            new OA\Response(response: 200, description: "Berhasil mengambil data limbah")
        ]
    )]
    public function index(Request $request): JsonResponse
    {
        $query = Textile::with('owner:id,name,whatsapp')
            ->orderByDesc('created_at');

        // Filter opsional
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('fabric_type')) {
            $query->where('fabric_type', 'like', '%'.$request->fabric_type.'%');
        }

        $textiles = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data'    => $textiles->items(),
            'meta'    => [
                'current_page' => $textiles->currentPage(),
                'last_page'    => $textiles->lastPage(),
                'total'        => $textiles->total(),
            ],
        ]);
    }

    /**
     * GET /api/v1/textiles/{id}
     * Detail satu limbah.
     */
    #[OA\Get(
        path: "/api/v1/textiles/{id}",
        summary: "Detail Limbah Kain",
        description: "Mengambil detail dari satu data limbah kain berdasarkan ID.",
        tags: ["Textiles"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, description: "ID limbah kain", schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Berhasil mengambil detail limbah")
        ]
    )]
    public function show(Textile $textile): JsonResponse
    {
        $textile->load(['owner:id,name,whatsapp', 'upcycler:id,name', 'product']);

        return response()->json([
            'success' => true,
            'data'    => $textile,
        ]);
    }

    /**
     * POST /api/v1/textiles
     * Kontributor menambah data limbah.
     */
    #[OA\Post(
        path: "/api/v1/textiles",
        summary: "Tambah Data Limbah",
        description: "Hanya untuk role Contributor. Menambahkan limbah baru ke sistem.",
        tags: ["Textiles"],
        security: [
            ["bearerAuth" => []]
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["title", "fabric_type", "weight", "address"],
                properties: [
                    new OA\Property(property: "title", type: "string", example: "Kain Perca Katun"),
                    new OA\Property(property: "fabric_type", type: "string", example: "Katun"),
                    new OA\Property(property: "weight", type: "number", example: 5.5),
                    new OA\Property(property: "address", type: "string", example: "Jl. Merdeka No. 10"),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "Limbah berhasil ditambahkan"),
            new OA\Response(response: 403, description: "Hanya Contributor yang bisa menambah")
        ]
    )]
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'fabric_type' => 'required|string|max:100',
            'description' => 'nullable|string',
            'weight'      => 'required|numeric|min:0.1',
            'address'     => 'required|string',
            'latitude'    => 'nullable|numeric',
            'longitude'   => 'nullable|numeric',
            'image'       => 'nullable|image|max:5120',
        ]);

        $user = auth('api')->user();

        if ($user->role !== 'contributor') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya Contributor yang bisa menambah limbah.',
            ], 403);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('textiles', 'public');
        }

        $textile = Textile::create([
            'user_id'     => $user->id,
            'title'       => $data['title'],
            'fabric_type' => $data['fabric_type'],
            'description' => $data['description'] ?? null,
            'weight'      => $data['weight'],
            'address'     => $data['address'],
            'latitude'    => $data['latitude']  ?? null,
            'longitude'   => $data['longitude'] ?? null,
            'image'       => $imagePath,
            'status'      => 'available',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Limbah berhasil ditambahkan.',
            'data'    => $textile,
        ], 201);
    }

    /**
     * PUT /api/v1/textiles/{id}
     * Kontributor mengupdate data limbah miliknya.
     */
    public function update(Request $request, Textile $textile): JsonResponse
    {
        $user = auth('api')->user();

        if ($textile->user_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Forbidden.'], 403);
        }

        $data = $request->validate([
            'title'       => 'sometimes|string|max:255',
            'fabric_type' => 'sometimes|string|max:100',
            'description' => 'nullable|string',
            'weight'      => 'sometimes|numeric|min:0.1',
            'address'     => 'sometimes|string',
            'latitude'    => 'nullable|numeric',
            'longitude'   => 'nullable|numeric',
        ]);

        $textile->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Limbah berhasil diupdate.',
            'data'    => $textile->fresh(),
        ]);
    }

    /**
     * DELETE /api/v1/textiles/{id}
     * Kontributor menghapus limbah miliknya (hanya jika masih available).
     */
    public function destroy(Textile $textile): JsonResponse
    {
        $user = auth('api')->user();

        if ($textile->user_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Forbidden.'], 403);
        }

        if ($textile->status !== 'available') {
            return response()->json([
                'success' => false,
                'message' => 'Limbah yang sudah diklaim tidak bisa dihapus.',
            ], 422);
        }

        if ($textile->image) {
            Storage::disk('public')->delete($textile->image);
        }

        $textile->delete();

        return response()->json([
            'success' => true,
            'message' => 'Limbah berhasil dihapus.',
        ]);
    }
}
