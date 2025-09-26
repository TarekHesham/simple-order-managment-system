<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\DTOs\SupplierData;
use App\Http\Controllers\Controller;
use App\Http\Requests\SupplierRequest;
use App\Http\Resources\Product\SupplierResource;
use App\Models\Product\Supplier;
use App\Services\SupplierService;
use Illuminate\Http\JsonResponse;

class SupplierController extends Controller
{
    public function __construct(protected SupplierService $service) {}

    public function index(): JsonResponse
    {
        $suppliers = $this->service->paginate();
        return $this->successResponsePaginate(
            SupplierResource::collection($suppliers),
            $suppliers,
            'Suppliers retrieved successfully'
        );
    }

    public function store(SupplierRequest $request): JsonResponse
    {
        $dto = SupplierData::fromArray($request->validated());
        $supplier = $this->service->create($dto);
        return $this->successResponse(SupplierResource::make($supplier), 'Supplier created successfully', 201);
    }

    public function show(Supplier $supplier): JsonResponse
    {
        return $this->successResponse(SupplierResource::make($supplier), 'Supplier retrieved successfully');
    }

    public function update(SupplierRequest $request, Supplier $supplier): JsonResponse
    {
        $dto = SupplierData::fromArray($request->validated());
        $updated = $this->service->update($supplier, $dto);
        return $this->successResponse(SupplierResource::make($updated), 'Supplier updated successfully');
    }

    public function destroy(Supplier $supplier): JsonResponse
    {
        $this->service->delete($supplier);
        return $this->successResponse([], 'Supplier deleted successfully');
    }
}
