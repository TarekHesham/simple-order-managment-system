<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\{StoreReceiveRequest, UpdateReceiveRequest};
use Illuminate\Http\JsonResponse;
use App\Services\ReceiveService;
use App\DTOs\ReceiveData;
use App\Http\Resources\Product\ReceiveResource;

class ReceiveController extends Controller
{
    public function __construct(private ReceiveService $service) {}

    public function index(): JsonResponse
    {
        $receives = $this->service->paginate(10);

        return $this->successResponsePaginate(
            ReceiveResource::collection($receives),
            $receives,
            'Receives retrieved successfully'
        );
    }

    public function store(StoreReceiveRequest $request): JsonResponse
    {
        $dto = ReceiveData::fromArray($request->validated());

        $receive = $this->service->create($dto);
        $receive->load(['product', 'supplier', 'user']);

        return $this->successResponse(
            ReceiveResource::make($receive),
            'Receive created successfully',
            201
        );
    }

    public function show(int $id): JsonResponse
    {
        $receive = $this->service->find($id);

        if (!$receive) {
            return $this->errorResponse('Receive not found', 404);
        }

        return $this->successResponse(ReceiveResource::make($receive), 'Receive retrieved successfully');
    }

    public function update(UpdateReceiveRequest $request, int $id): JsonResponse
    {
        $receive = $this->service->find($id);

        if (!$receive) {
            return $this->errorResponse('Receive not found', 404);
        }

        $dto = ReceiveData::fromArray($request->validated() + $receive->except(['id', 'created_at', 'updated_at']));

        $updated = $this->service->update($receive, $dto);
        $updated->load(['product', 'supplier', 'user']);

        return $this->successResponse(
            ReceiveResource::make($updated),
            'Receive updated successfully'
        );
    }

    public function destroy(int $id): JsonResponse
    {
        $receive = $this->service->find($id);

        if (!$receive) {
            return $this->errorResponse('Receive not found', 404);
        }

        $this->service->delete($receive);

        return $this->successResponse([], 'Receive deleted successfully');
    }
}
