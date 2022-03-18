<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Global Success Response
     *
     * @param string|array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateResponse($message = null, $data = null)
    {
        $response = ['status' => 'ok'];
        if (!empty($message)) {
            $response = ['message' => $message, ...$response];
        }

        if (!empty($data)) {
            $response = ['data' => $data, ...$response];
        }

        return response()->json($response);
    }

    /**
     * Global Error Response
     *
     * @param string|null $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateErrorResponse($message = null, $status = 400)
    {
        $response = ['status' => 'fail'];
        if (!empty($message)) {
            $response = ['message' => $message, ...$response];
        }

        return response()->json($response, $status);
    }
}
