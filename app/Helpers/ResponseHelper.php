<?php

namespace App\Helpers;

class ResponseHelper
{
    public static function errorResponse($e, $code = 500)
    {
        return response()->json([
            'status' => false,
            'message' => $e->getMessage(),
        ], $code);
    }

    public static function successResponse($message = 'Berhasil', $code = 200)
    {
        return response()->json([
            'status' => true,
            'message' => $message,
        ], $code);
    }

    public static function getDataSuccessResponse($message = 'Berhasil', $data = null, $code = 200)
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    public static function validationErrorResponse($validator, $message = 'Validasi gagal', $code = 422)
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'errors' => $validator->errors()
        ], $code);
    }
}
