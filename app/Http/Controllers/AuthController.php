<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['loginAction', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function loginAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_name' => 'required',
            'password' => 'required'
        ]);

        $response = array(
            'status' => 'failed',
            'message' => 'Gagal Login!'
        );

        if($validator->fails()) {
            return response()->json([
               'status' => 'failed',
               'type' => 'validate',
               'message' => $validator->errors()
            ], 422);
        } else {
            // to create JWT Token using credential (because password stored in DB is md5 hashed, so I have to change vendor Illuminate\Auth\EloquentUserProvider::validateCredentials to if(md5($plain)==$user->getAuthPassword()))
            $token = auth('api')->attempt($validator->validated());

            if(!$token) {
                // condition if credential is not valid
                $response['message'] = 'Unauthorized';
                $status_code = 401;
            } else {
                // condition if credential is valid
                $status_code = 200;
                $response = array(
                    'status' => 'success',
                    'message' => 'Login berhasil',
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    'expires_in' => (auth('api')->factory()->getTTL() / 1440).' days',
                    'data' => auth('api')->user()
                );
            }

            return response()->json($response, $status_code);
        }
    }

    /**
     * Log the user out
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logoutAction()
    {
        auth('api')->logout();

        return response()->json(['status' => 'success', 'message' => 'User successfully logged out']);
    }
}
