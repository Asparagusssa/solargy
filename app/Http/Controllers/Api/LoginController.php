<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function index(Request $request)
    {
        $attrs = request()->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $request->email)->first();

        if(!$user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'error' => 'Не верный логин или пароль',
            ])->status(401);
        }

        Auth::login($user);
        $token = $user->createToken($request->email)->plainTextToken;

        request()->session()->regenerate();

        return response()->json(['token' => $token], 200);
    }
}
