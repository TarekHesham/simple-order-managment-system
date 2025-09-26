<?php

namespace App\Http\Controllers\Api\V1\Staff;

use App\Services\ProductService;
use App\Http\Controllers\Controller;
use App\Http\Resources\Product\ProductResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $service
    ) {
        // 
    }

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
}
