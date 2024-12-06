<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function index(Request $request)
    {
        $attrs = request()->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required'],
        ]);

        if(! Auth::attempt($attrs) ) {
            throw ValidationException::withMessages([
                'error' => 'Не верный логин или пароль',
            ])->status(401);
        }

        request()->session()->regenerate();

        return response()->json('Авторизация прошла успешно', 200);
    }
}
