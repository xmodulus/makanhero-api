<?php

namespace App\Http\Controllers;

use App\Http\Requests\Authenticate;
use JWTAuth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Auth;


class AuthenticateController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.refresh')->only('refresh');
    }
    public function authenticate(Authenticate $request)
    {
        $credentials['email']       = $request->email;
        $credentials['password']    = Hash::make($request->password);

        try {
            // verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // if no errors are encountered we can return a JWT
        return response()->json(compact('token'));
    }

    /**
     * Refresh Token
     *
     * * **Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*
     * Check Authorization header for new token.
     * Call this API to exchange expired (not invalid!) JWT token with a fresh one.
     *
     */
    public function refresh()
    {
        return response()->json(['status' => 'Ok', 'message' => 'Check Authorization Header for new token']);
    }

    /**
     * Get Authenticated User
     *
     * **Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*
     *
     * Retrieves the user associated with the JWT token.
     *
     */
    public function getAuthenticatedUser()
    {
        $user = Auth::user();

        // the token is valid and we have found the user via the sub claim
        return response()->json([
            'user' => $user,
        ], 200);
    }

}