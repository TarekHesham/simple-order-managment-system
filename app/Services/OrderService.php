<?php

namespace App\Services;

use App\DTOs\OrderData;
use App\Events\{OrderCreated, OrderUpdated};
use App\Models\Product\Product;
use App\Models\Sale\{Order, OrderItem, OrderItemReturn};
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class OrderService
{
    public function createOrder(OrderData $dto): Order
    {
        return DB::transaction(function () use ($dto) {
            $order = Order::create([
                'customer_id'     => $dto->customer_id,
                'total_amount'    => 0,
                'refunded_amount' => 0,
                'status'          => 'pending',
            ]);

            $total = 0;
            $items = collect();

            $productIds = collect($dto->items)->pluck('product_id')->all();

            // Load all products in one query
            $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

            foreach ($dto->items as $itemDto) {
                $product = $products->get($itemDto->product_id);

                if (! $product || ! $product->is_active) {
                    throw new \Exception("Product ID {$itemDto->product_id} not found");
                }

                if ($product->stock < $itemDto->quantity) {
                    throw new \Exception("Product {$product->name} out of stock");
                }

                $unitPrice = (float) $product->price;

                $quantity  = $itemDto->quantity;
                $lineTotal = round($unitPrice * $quantity, 2);

                $items->push([
                    'order_id'          => $order->id,
                    'product_id'        => $product->id,
                    'quantity'          => $quantity,
                    'unit_price'        => $unitPrice,
                    'total_price'       => $lineTotal,
                    'refunded_quantity' => 0,
                    'refunded_amount'   => 0,
                ]);

                $total += $lineTotal;
            }

            if ($items->isNotEmpty()) {
                OrderItem::insert($items->toArray());
            }

            $order->total_amount = round($total, 2);
            $order->status = 'completed';
            $order->save();

            // Event dispatched after order is created
            OrderCreated::dispatch($order, $items, Auth::user());

            return $order->load(['customer', 'items.product', 'sale.user']);
        });
    }

    public function listOrders(int $perPage = 15): LengthAwarePaginator
    {
        return Order::with(['customer', 'items.product', 'sale.user'])
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    public function refundOrder(Order $order, float $amount, ?string $reason = null, ?int $processedBy = null): Order
    {
        return DB::transaction(function () use ($order, $amount, $reason, $processedBy) {
            $amount = round($amount, 2);

            if ($amount <= 0) {
                throw new \InvalidArgumentException('Refund amount must be greater than zero.');
            }

            $availableToRefund = round($order->total_amount - $order->refunded_amount, 2);

            if ($amount > $availableToRefund) {
                throw new \InvalidArgumentException('Refund amount exceeds available refundable amount.');
            }

            $items = $order->items()->lockForUpdate()->get();

            $remaining    = $amount;
            $distributed  = 0;
            $refundedItems = collect();
            $stockUpdates  = []; // لتجميع الكميات الراجعة للمخزون

            foreach ($items as $index => $item) {
                if ($remaining <= 0) break;

                $itemRemaining = round($item->total_price - $item->refunded_amount, 2);
                if ($itemRemaining <= 0) continue;

                $take = min($itemRemaining, round(($item->total_price / $order->total_amount) * $amount, 2));

                // آخر Item بياخد المتبقي علشان ميحصلش rounding gaps
                if ($index === $items->count() - 1) {
                    $take = min($itemRemaining, $remaining);
                }

                if ($take <= 0) continue;

                $proportionalQty = 0;
                if ($item->unit_price > 0) {
                    $proportionalQty = (int) floor($take / $item->unit_price);
                }

                $refundedItems->push([
                    'order_item_id' => $item->id,
                    'processed_by'  => $processedBy,
                    'quantity'      => $proportionalQty,
                    'reason'        => $reason,
                    'amount'        => $take,
                ]);

                $item->refunded_amount   = round($item->refunded_amount + $take, 2);
                $item->refunded_quantity = $item->refunded_quantity + $proportionalQty;
                $item->save();

                // هنا بدل ما نعمل increment لكل منتج بنجمعهم
                if ($proportionalQty > 0) {
                    if (!isset($stockUpdates[$item->product_id])) {
                        $stockUpdates[$item->product_id] = 0;
                    }
                    $stockUpdates[$item->product_id] += $proportionalQty;
                }

                $distributed += $take;
                $remaining    = round($remaining - $take, 2);
            }

            if ($refundedItems->isNotEmpty()) {
                OrderItemReturn::insert($refundedItems->toArray());
            }

            if ($remaining > 0) {
                $itemsReturn = collect();
                foreach ($items as $item) {
                    $itemRemaining = round($item->total_price - $item->refunded_amount, 2);
                    if ($itemRemaining <= 0) continue;

                    $take = min($itemRemaining, $remaining);

                    $itemsReturn->push([
                        'order_item_id' => $item->id,
                        'processed_by'  => $processedBy,
                        'quantity'      => 0,
                        'reason'        => $reason,
                        'amount'        => $take,
                    ]);

                    $item->refunded_amount = round($item->refunded_amount + $take, 2);
                    $item->save();

                    $distributed += $take;
                    $remaining    = round($remaining - $take, 2);

                    if ($remaining <= 0) break;
                }

                if ($itemsReturn->isNotEmpty()) {
                    OrderItemReturn::insert($itemsReturn->toArray());
                }
            }

            // هنا نعمل Bulk update للمخزون
            foreach ($stockUpdates as $productId => $qty) {
                Product::where('id', $productId)->lockForUpdate()->increment('stock', $qty);
            }

            $order->refunded_amount = round($order->refunded_amount + $distributed, 2);

            if (bccomp((string) $order->refunded_amount, (string) $order->total_amount, 2) === 0) {
                $order->status = 'refunded';
            } else {
                $order->status = 'partial_refund';
            }

            $order->save();

            OrderUpdated::dispatch($order, Auth::user());

            return $order->load(['customer', 'items.product', 'items.returns']);
        });
    }

    public function refundOrderItem(OrderItem $item, int $quantity, ?float $amount = null, ?string $reason = null, ?int $processedBy = null): OrderItem
    {
        return DB::transaction(function () use ($item, $quantity, $amount, $reason, $processedBy) {
            if ($quantity <= 0) {
                throw new \InvalidArgumentException('Refund quantity must be greater than zero.');
            }

            $availableQty = $item->quantity - $item->refunded_quantity;
            if ($quantity > $availableQty) {
                throw new \InvalidArgumentException('Refund quantity exceeds available refundable quantity for this item.');
            }

            if ($amount === null) {
                $amount = round($item->unit_price * $quantity, 2);
            } else {
                $amount = round($amount, 2);
                if ($amount > ($item->total_price - $item->refunded_amount)) {
                    throw new \InvalidArgumentException('Refund amount exceeds refundable amount for this item.');
                }
            }

            OrderItemReturn::create([
                'order_item_id' => $item->id,
                'processed_by'  => $processedBy,
                'quantity'      => $quantity,
                'reason'        => $reason,
                'amount'        => $amount,
            ]);

            $item->refunded_quantity = $item->refunded_quantity + $quantity;
            $item->refunded_amount   = round($item->refunded_amount + $amount, 2);
            $item->save();

            $order = $item->order()->lockForUpdate()->first();
            $order->refunded_amount = round($order->refunded_amount + $amount, 2);

            if (bccomp((string)$order->refunded_amount, (string)$order->total_amount, 2) === 0) {
                $order->status = 'refunded';
            } else {
                $order->status = 'partial_refund';
            }

            $item->product()->lockForUpdate()->increment('stock', $quantity);

            $order->save();

            OrderUpdated::dispatch($order, Auth::user());

            return $item->load(['order', 'returns']);
        });
    }
}
