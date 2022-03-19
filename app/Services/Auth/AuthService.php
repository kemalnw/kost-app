<?php

namespace App\Services\Auth;

use App\Events\Auth\UserRegistered;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use App\Services\Auth\Traits\AuthenticatesUsers;
use Illuminate\Support\Facades\DB;

class AuthService
{
    use AuthenticatesUsers;

    /**
     * Construct
     *
     * @param UserRepository $repository
     */
    public function __construct(
        protected UserRepository $repository)
    {}

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Models\User\User
     */
    public function register(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = $this->repository->create([
                'role_id' => $request->role,
                'password' => bcrypt($request->password),
                ...$request->all()
            ]);

            UserRegistered::dispatch($user);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $user;
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // We can automatically throttle the login attempts for this application.
        // We'll key this by the username and the IP address of the client making these requests
        // into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if (!$this->attemptLogin($request)) {
            // If the login attempt was unsuccessful we will increment the number of attempts
            // to login. Of course, when this user surpasses their maximum number of attempts
            // they will get locked out.
            $this->incrementLoginAttempts($request);
            throw ValidationException::withMessages([
                $this->username() => [trans('auth.failed')],
            ])->status(Response::HTTP_UNAUTHORIZED);
        }

        return $request->user()->createToken(config('app.name'))->plainTextToken;
    }
}
