<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\DTOs\CustomerData;
use App\Services\CustomerService;
use App\Http\Controllers\Controller;
use App\Http\Requests\{StoreCustomerRequest, UpdateCustomerRequest};
use App\Http\Resources\CustomerResource;
use Illuminate\Http\{JsonResponse, Request};

class CustomerController extends Controller
{
    public function __construct(
        protected CustomerService $service
    ) {
        // 
    }

    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('q');

        if (! is_int($perPage)) {
            return $this->errorResponse('Per page must be an integer');
        }

        $customers = $this->service->paginate($perPage, $search);

        if ($customers->isEmpty()) {
            return $this->errorResponse('Customers not found');
        }

        return $this->successResponsePaginate(
            CustomerResource::collection($customers)->items(),
            $customers,
            'Customers retrieved successfully'
        );
    }

    public function store(StoreCustomerRequest $request): JsonResponse
    {
        $dto = CustomerData::fromArray($request->validated());
        $customer = $this->service->create($dto);
        return $this->successResponse($customer, 'Customer created successfully', 201);
    }

    public function show(int $id): JsonResponse
    {
        $customer = $this->service->find($id);

        if (! $customer) {
            return $this->errorResponse('Customer not found', 404);
        }

        return $this->successResponse(
            CustomerResource::make($customer),
            'Customer retrieved successfully'
        );
    }

    public function update(UpdateCustomerRequest $request, int $id): JsonResponse
    {
        $dto = CustomerData::fromArray($request->validated());
        $customer = $this->service->update($id, $dto);
        if (! $customer) {
            return $this->errorResponse('Customer not found', 404);
        }
        return $this->successResponse(
            CustomerResource::make($customer),
            'Customer updated successfully'
        );
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->service->delete($id);
        if (! $deleted) {
            return $this->errorResponse('Customer not found', 404);
        }
        return $this->successResponse([], 'Customer deleted successfully');
    }
}
