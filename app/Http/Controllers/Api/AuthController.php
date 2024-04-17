<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
 * Class AuthController
 * @package App\Http\Controllers\Api
 */
class AuthController extends Controller
{
    /**
     * Register a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login' => 'required|string|max:255',
            'password' => 'required|string|min:8',
            'email' => 'required|string|email|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birthday' => 'nullable|date',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
                'result' => null
            ]);
        }
        $user = User::create([
            'login' => $request->login,
            'password' => Hash::make($request->password),
            'email' => $request->email,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'birthday' => $request->birthday,
        ]);
        return response()->json([
            'error' => null,
            'user' => $user,
        ]);
    }

    /**
     * Login a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login' => 'required|string|max:255',
            'password' => 'required|string|min:8',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
                'result' => null
            ]);
        }
        $user = User::where('login', $request->login)->first();
        if (! $user) {
            return response()->json([
                'error' => 'User not found',
                'result' => null
            ]);
        }
        if (! bcrypt($request->password) === $user->password) {
            return response()->json([
                'error' => 'Invalid password',
                'result' => null
            ]);
        }
        return response()->json([
            'error' => null,
            'user' => $user,
        ]);
    }
}
