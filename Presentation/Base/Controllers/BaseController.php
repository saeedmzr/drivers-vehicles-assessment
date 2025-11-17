<?php
namespace Presentation\Base\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Presentation\Base\Responses\BaseResponse;

class BaseController extends Controller
{
    public function success(mixed $data = [], mixed $meta = null, int $status = 200): JsonResponse
    {
        if ($data instanceof BaseResponse) {
            $data = $data->toJson();
        }
        return response()->json([
            'success' => true,
            'data' => $data,
            'meta' => $meta,
        ], status: $status);
    }

    public function failed(\Throwable $exception, array $data = [], int $status = 400): JsonResponse
    {
        return response()->json(
            [
                'success' => false,
                'error' => $data
            ],
            $status
        );
    }

}
