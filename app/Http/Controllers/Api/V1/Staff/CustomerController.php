<?php

namespace App\Http\Controllers\Api\V1\Staff;

use App\Services\CustomerService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;

class CustomerController extends Controller
{
    public function __construct(
        protected CustomerService $service
    ) {}

    public function index(): JsonResponse
    {
        $customers = $this->service->paginate();
        return $this->successResponsePaginate(CustomerResource::collection($customers), $customers, 'Customers retrieved successfully');
    }

    public function show(int $id): JsonResponse
    {
        $customer = $this->service->find($id);
        if (! $customer) {
            return $this->errorResponse('Customer not found', 404);
        }
        return $this->successResponse(CustomerResource::make($customer), 'Customer retrieved successfully');
    }
}
