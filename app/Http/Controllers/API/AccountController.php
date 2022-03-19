<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Retrieve current logged in user
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function currentUser(Request $request)
    {
        UserResource::withoutWrapping();

        return UserResource::make($request->user());
    }
}
