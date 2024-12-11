<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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

        Mail::send('emails.contact', $validatedData, function ($message) use ($validatedData) {
            $message->from($validatedData['email'], $validatedData['name']);
            $message->to('your_yandex_email@yandex.com')->subject('New Contact Form Submission');
        });

        return response()->json('OK', 200);
    }
}
