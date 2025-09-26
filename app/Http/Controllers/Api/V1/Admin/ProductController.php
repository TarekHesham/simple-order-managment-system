<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\DTOs\ProductData;
use App\Services\ProductService;
use App\Http\Controllers\Controller;
use App\Http\Requests\{StoreProductRequest, UpdateProductRequest};
use App\Http\Resources\Product\ProductResource;
use App\Models\Product\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(private readonly ProductService $service) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 10);

        if (! is_int($perPage)) {
            return $this->errorResponse('Per page must be an integer');
        }

        $products = $this->service->paginate($perPage);

        if ($products->isEmpty()) {
            return $this->errorResponse('Products not found');
        }

        return $this->successResponsePaginate(
            ProductResource::collection($products),
            $products,
            'Products retrieved successfully'
        );
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $dto = ProductData::fromArray($request->validated());
        $product = $this->service->store($dto);
        return $this->successResponse(
            ProductResource::make($product),
            'Product created successfully'
        );
    }

    public function show(Product $product): JsonResponse
    {
        return $this->successResponse(
            ProductResource::make($product),
            'Product retrieved successfully'
        );
    }

    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        $dto = ProductData::fromArray($request->validated() + [
            'name'        => $product->name,
            'description' => $product->description,
            'price'       => $product->price,
            'is_active'   => $product->is_active,
        ]);
        $updated = $this->service->update($product, $dto);
        return $this->successResponse(
            ProductResource::make($updated),
            'Product updated successfully'
        );
    }

    public function destroy(Product $product): JsonResponse
    {
        $deleted = $this->service->delete($product);
        if (! $deleted) {
            return $this->errorResponse('Failed to delete product');
        }
        return $this->successResponse([], 'Product deleted successfully');
    }
}
