<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * @group Auth endpoint
 */
class LoginController extends Controller
{
    /**
     * POST Login
     *
     * Login a user.
     *
     * @response {"access_token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9"}
     * @response 422 {"email": ["The provided credentials are incorrect."]}
     */
    public function __invoke(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'email' => ['The provided credentials are incorrect.'],
            ], 422);
        }

        $device = substr($request->userAgent(), 0, 255);

        return response()->json([
            'access_token' => $user->createToken($device)->plainTextToken,
        ]);
    }
}
