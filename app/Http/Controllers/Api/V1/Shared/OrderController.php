<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\{
    StoreOrderRequest,
    RefundOrderRequest,
    RefundOrderItemRequest
};
use App\Services\OrderService;
use App\DTOs\OrderData;
use App\Http\Resources\Sale\OrderResource;
use App\Models\Sale\{Order, OrderItem};
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    protected OrderService $service;

    public function __construct(OrderService $service)
    {
        $this->service = $service;
    }

    /**
     * List orders (paginated)
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 15);
        $paginator = $this->service->listOrders($perPage);

        return $this->successResponsePaginate(
            OrderResource::collection($paginator->items())->resolve(),
            $paginator,
            'Orders fetched successfully'
        );
    }

    /**
     * Create an order
     */
    public function store(StoreOrderRequest $request): JsonResponse
    {
        $dto = OrderData::fromArray($request->validated());

        try {
            $order = $this->service->createOrder($dto);
            return $this->successResponse(new OrderResource($order), 'Order created successfully', 201);
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }
    }

    /**
     * Refund entire order (or partial amount)
     * @param Request $request
     */
    public function refundOrder(RefundOrderRequest $request, Order $order): JsonResponse
    {
        $amount = (float) $request->input('amount');
        $reason = $request->input('reason', null);
        $processedBy = Auth::id();

        try {
            $order = $this->service->refundOrder($order, $amount, $reason, $processedBy);
            return $this->successResponse(new OrderResource($order), 'Order refunded successfully');
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }
    }

    /**
     * Refund a specific order item
     * @param Request $request
     */
    public function refundItem(RefundOrderItemRequest $request, Order $order, OrderItem $item): JsonResponse
    {
        // ensure item belongs to order
        if ($item->order_id !== $order->id) {
            return $this->errorResponse('Order item does not belong to order.', 404);
        }

        $quantity = (int) $request->input('quantity');
        $amount = $request->has('amount') ? (float) $request->input('amount') : null;
        $reason = $request->input('reason', null);
        $processedBy = Auth::id();

        try {
            $item = $this->service->refundOrderItem($item, $quantity, $amount, $reason, $processedBy);
            return $this->successResponse($item->fresh(), 'Order item refunded successfully');
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }
    }
}
