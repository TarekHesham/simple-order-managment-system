<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function successResponsePaginate($data = [], $paginate, $message = null, $code = 200)
    {
        return response()->json([
            'status'  => true,
            'message' => $message,
            'data'    => $data,
            'meta' => [
                'current_page' => $paginate->currentPage(),
                'last_page'    => $paginate->lastPage(),
                'per_page'     => $paginate->perPage(),
                'total'        => $paginate->total(),
            ],
        ], $code);
    }

    public function successResponse($data = [], $message = null, $code = 200)
    {
        return response()->json([
            'status'  => true,
            'message' => $message,
            'data'    => $data,
        ], $code);
    }

    public function errorResponse($message, $code = 404)
    {
        return response()->json([
            'status'  => false,
            'message' => $message,
            'data'    => [],
        ], $code);
    }
}
