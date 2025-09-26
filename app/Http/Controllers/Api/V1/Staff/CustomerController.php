<?php

namespace App\Http\Controllers\Api\V1\Staff;

use App\Services\CustomerService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class CustomerController extends Controller
{
    public function __construct(
        protected CustomerService $service
    ) {}

    public function index(): JsonResponse
    {
        $customers = $this->service->paginate();
        return $this->successResponsePaginate($customers->items(), $customers, 'Customers retrieved successfully');
    }

    public function show(int $id): JsonResponse
    {
        $customer = $this->service->find($id);
        if (! $customer) {
            return $this->errorResponse('Customer not found', 404);
        }
        return $this->successResponse($customer, 'Customer retrieved successfully');
    }
}
