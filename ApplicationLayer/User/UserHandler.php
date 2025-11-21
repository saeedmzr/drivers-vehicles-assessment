<?php

namespace ApplicationLayer\User;

use Domain\User\Contracts\UserUseCaseInterface;
use Illuminate\Http\Request;
use Presentation\User\Requests\LoginRequest;
use Presentation\User\Responses\UserResponse;

class UserHandler
{
    public function __construct(
        private readonly UserUseCaseInterface $userUseCase
    ) {
    }

    /**
     * Authenticate user and return token
     */
    public function login(LoginRequest $request): UserResponse
    {
        $user = $this->userUseCase->authenticate($request->getEmail(), $request->getPassword());

        if (!$user) {
            throw new \Exception('Invalid credentials');
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return new UserResponse(
            id: $user->id,
            name: $user->name,
            email: $user->email,
            token: $token
        );
    }

    /**
     * Logout user (revoke token)
     */
    public function logout(Request $request): void
    {
        auth('api')->logout();

    }

    /**
     * Get authenticated user
     */
    public function get(Request $request): UserResponse
    {
        $user = $request->user();

        return new UserResponse(
            id: $user->id,
            name: $user->name,
            email: $user->email
        );
    }
}
