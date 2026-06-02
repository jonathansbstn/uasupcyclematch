<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

/**
 * OrderApiController
 *
 * Endpoint untuk pesanan produk upcycle.
 * Semua endpoint dilindungi JWT.
 */
class OrderApiController extends Controller
{
    /**
     * GET /api/v1/orders
     * Contributor: lihat pesanan sendiri.
     * Upcycler: lihat pesanan masuk untuk produk mereka.
     */
    #[OA\Get(
        path: "/api/v1/orders",
        summary: "Daftar Pesanan",
        description: "Melihat daftar pesanan. Contributor melihat pesanannya, Upcycler melihat pesanan masuk.",
        tags: ["Orders"],
        security: [
            ["bearerAuth" => []]
        ],
        responses: [
            new OA\Response(response: 200, description: "Berhasil mengambil data pesanan")
        ]
    )]
    public function index(): JsonResponse
    {
        $user = auth('api')->user();

        if ($user->role === 'contributor') {
            $orders = Order::where('buyer_id', $user->id)
                ->with(['product:id,product_name,price,photo,upcycler_id', 'product.upcycler:id,name,whatsapp'])
                ->orderByDesc('created_at')
                ->get();
        } elseif ($user->role === 'upcycler') {
            $orders = Order::whereHas('product', fn($q) => $q->where('upcycler_id', $user->id))
                ->with(['product:id,product_name,price,photo', 'buyer:id,name,whatsapp'])
                ->orderByDesc('created_at')
                ->get();
        } else {
            // Admin: semua pesanan
            $orders = Order::with(['product:id,product_name,price', 'buyer:id,name'])
                ->orderByDesc('created_at')
                ->paginate(20);

            return response()->json([
                'success' => true,
                'data'    => $orders->items(),
                'meta'    => ['total' => $orders->total(), 'current_page' => $orders->currentPage()],
            ]);
        }

        return response()->json([
            'success' => true,
            'data'    => $orders,
        ]);
    }

    /**
     * GET /api/v1/orders/{id}
     * Detail satu pesanan.
     */
    #[OA\Get(
        path: "/api/v1/orders/{id}",
        summary: "Detail Pesanan",
        description: "Mengambil detail dari satu data pesanan berdasarkan ID.",
        tags: ["Orders"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, description: "ID pesanan", schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Berhasil mengambil detail pesanan")
        ]
    )]
    public function show(Order $order): JsonResponse
    {
        $user = auth('api')->user();

        // Hanya buyer, upcycler pemilik produk, atau admin yang boleh lihat
        $allowed = $order->buyer_id === $user->id
            || ($order->product && $order->product->upcycler_id === $user->id)
            || $user->role === 'admin';

        if (!$allowed) {
            return response()->json(['success' => false, 'message' => 'Forbidden.'], 403);
        }

        $order->load(['product.upcycler:id,name,whatsapp', 'buyer:id,name,whatsapp']);

        return response()->json([
            'success' => true,
            'data'    => $order,
        ]);
    }

    /**
     * POST /api/v1/orders
     * Contributor membuat pesanan baru.
     */
    #[OA\Post(
        path: "/api/v1/orders",
        summary: "Buat Pesanan Baru",
        description: "Hanya untuk role Contributor. Membuat pesanan baru untuk membeli produk Upcycle.",
        tags: ["Orders"],
        security: [
            ["bearerAuth" => []]
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["product_id", "quantity", "recipient_name", "recipient_phone", "address", "city", "payment_method"],
                properties: [
                    new OA\Property(property: "product_id", type: "integer", example: 1),
                    new OA\Property(property: "quantity", type: "integer", example: 1),
                    new OA\Property(property: "recipient_name", type: "string", example: "Andi"),
                    new OA\Property(property: "recipient_phone", type: "string", example: "08123456789"),
                    new OA\Property(property: "address", type: "string", example: "Jl. Sudirman 10"),
                    new OA\Property(property: "city", type: "string", example: "Jakarta"),
                    new OA\Property(property: "payment_method", type: "string", enum: ["koin", "transfer"], example: "transfer"),
                    new OA\Property(property: "notes", type: "string", example: "Tolong packing rapi"),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "Pesanan berhasil dibuat")
        ]
    )]
    public function store(Request $request): JsonResponse
    {
        $user = auth('api')->user();

        if ($user->role !== 'contributor') {
            return response()->json(['success' => false, 'message' => 'Hanya Contributor yang bisa membuat pesanan.'], 403);
        }

        $data = $request->validate([
            'product_id'     => 'required|integer|exists:products,id',
            'quantity'       => 'required|integer|min:1|max:10',
            'recipient_name' => 'required|string|max:255',
            'recipient_phone'=> 'required|string|max:20',
            'address'        => 'required|string',
            'city'           => 'required|string|max:100',
            'payment_method' => 'required|in:koin,transfer',
            'notes'          => 'nullable|string|max:500',
        ]);

        $product = Product::findOrFail($data['product_id']);

        if ($product->status !== 'published') {
            return response()->json(['success' => false, 'message' => 'Produk tidak tersedia.'], 422);
        }

        $totalPrice = $product->price * $data['quantity'];

        // Jika bayar koin, cek saldo
        if ($data['payment_method'] === 'koin') {
            if ($user->koin < $totalPrice) {
                return response()->json([
                    'success' => false,
                    'message' => "Koin tidak cukup. Saldo koin Anda: {$user->koin}, dibutuhkan: {$totalPrice}.",
                ], 422);
            }
            // Potong koin
            $user->decrement('koin', $totalPrice);
        }

        $order = Order::create([
            'buyer_id'        => $user->id,
            'product_id'      => $product->id,
            'quantity'        => $data['quantity'],
            'total_price'     => $totalPrice,
            'recipient_name'  => $data['recipient_name'],
            'recipient_phone' => $data['recipient_phone'],
            'address'         => $data['address'],
            'city'            => $data['city'],
            'payment_method'  => $data['payment_method'],
            'notes'           => $data['notes'] ?? null,
            'status'          => $data['payment_method'] === 'koin' ? 'paid' : 'pending',
            'paid_at'         => $data['payment_method'] === 'koin' ? now() : null,
        ]);

        return response()->json([
            'success'  => true,
            'message'  => 'Pesanan berhasil dibuat.',
            'data'     => $order->load('product:id,product_name,price'),
        ], 201);
    }

    /**
     * PATCH /api/v1/orders/{id}/status
     * Upcycler mengupdate status pesanan mereka.
     */
    public function updateStatus(Request $request, Order $order): JsonResponse
    {
        $user = auth('api')->user();

        if (!$order->product || $order->product->upcycler_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Forbidden.'], 403);
        }

        $data = $request->validate([
            'status' => 'required|in:processing,shipped,done,cancelled',
        ]);

        $order->update(['status' => $data['status']]);

        return response()->json([
            'success' => true,
            'message' => 'Status pesanan diperbarui.',
            'data'    => ['order_id' => $order->id, 'status' => $order->status],
        ]);
    }
}
