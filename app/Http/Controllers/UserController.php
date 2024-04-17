<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function view($id)
    {
        return view('user', ['user' => User::findOrFail($id)]);
    }
}
