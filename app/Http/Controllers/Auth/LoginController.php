<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login' => 'required|string|max:255',
            'password' => 'required|string|min:8',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput($request->only('login', 'password'))
                ->withErrors($validator);
        }
        $credentials = $request->only('login', 'password');
        if (Auth::attempt($credentials)) {
            $user = User::where('login', $credentials['login'])->first();
            Auth::login($user);
            return redirect()->route('welcome');
        }
        return redirect()->back()->withInput($request->only('login', 'password'))
            ->withErrors([
                'login' => 'Invalid login or password.',
            ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('welcome');
    }
}