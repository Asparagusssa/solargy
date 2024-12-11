<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\Support;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SupportController extends Controller
{
    public function submitForm(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'comment' => 'required|string',
        ]);

        Mail::to('grechapo40@yandex.ru')->send(new Support(['name' => $validatedData['name'], 'email' => $validatedData['email'], 'comment' => $validatedData['comment']]));

        return response()->json('OK', 200);
    }
}
