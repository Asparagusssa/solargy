<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\Call;
use App\Mail\Support;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CallController extends Controller
{
    public function submitForm(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string',
            'comment' => 'required|string',
        ]);

        Mail::to('grechapo40@yandex.ru')->send(new Call(['name' => $validatedData['name'], 'phone' => $validatedData['phone'], 'comment' => $validatedData['comment']]));

        return response()->json('OK', 200);
    }
}
