<?php

namespace App\Traits;

trait ApiResponse
{

    protected function successResponse($data, $message = "Thực hiện thành công", $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'error_code' => null
        ], $code);
    }


    protected function errorResponse($message, $errorCode = "ERROR_UNDEFINED", $code = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'error_code' => $errorCode
        ], $code);
    }
}
