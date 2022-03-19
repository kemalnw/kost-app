<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\Auth\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Construct
     *
     * @param AuthService $service
     */
    public function __construct(
        protected AuthService $service
    ){}

    /**
     * Register a new user
     *
     * @param RegisterRequest $request
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterRequest $request)
    {
        UserResource::withoutWrapping();

        return UserResource::make(
            $this->service->register($request)
        );
    }

    /**
     * Handle login to the app
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        return $this->generateResponse('Successfully Login.', [
            'token' => $this->service->login($request),
        ]);
    }
}
