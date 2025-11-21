<?php

namespace Presentation\User\Controllers;

use ApplicationLayer\User\UserHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Presentation\Base\Controllers\BaseController;
use Presentation\User\Requests\LoginRequest;

class UserController extends BaseController
{
    public function __construct(
        private readonly UserHandler $userHandler
    ) {
    }

    /**
     * Authenticate user and return token
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $response = $this->userHandler->login($request);
            return $this->success($response, status: 200);
        } catch (\Throwable $e) {
            return $this->failed($e, ['message' => $e->getMessage()], 401);
        }
    }

    /**
     * Logout user (revoke token)
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $this->userHandler->logout($request);
            return $this->success(['message' => 'Logged out successfully']);
        } catch (\Throwable $e) {
            return $this->failed($e, ['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get authenticated user
     */
    public function get(Request $request): JsonResponse
    {
        try {
            $response = $this->userHandler->get($request);
            return $this->success($response);
        } catch (\Throwable $e) {
            return $this->failed($e, ['message' => $e->getMessage()], 500);
        }
    }
}
