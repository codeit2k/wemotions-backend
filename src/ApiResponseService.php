<?php

namespace App\Service;

class ApiResponseService
{
    public function success($data = null, string $message = 'Success', $pagination = null, int $statusCode = 200)
    {
        $response = [
            'success' => true,
            'data' => $data,
            'message' => $message,
        ];

        if ($pagination !== null) {
            $response['pagination'] = $pagination;
        }

        return [$response, $statusCode];
    }

    public function error(string $message = 'Error', int $statusCode = 400, $errors = null)
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return [$response, $statusCode];
    }
}
