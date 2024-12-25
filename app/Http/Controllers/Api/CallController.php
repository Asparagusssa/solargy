<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\Call;
use App\Mail\Support;
use App\Models\Email;
use App\Models\EmailType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CallController extends Controller
{
    public function submitForm(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string',
            'comment' => 'sometimes',
            'email-type' => 'required|exists:email_types,id',
        ]);

        $type = EmailType::findOrFail($request->input('email-type'));
        $emailAddresses = $type->emails->pluck('email')->toArray();

        $validatedData['comment'] = $request->input('comment') ?? 'Пустое сообщение';
        Mail::to($emailAddresses)->send(new Call([
            'name' => $validatedData['name'],
            'phone' => $validatedData['phone'],
            'comment' => $validatedData['comment']
        ]));

        return response()->json('OK', 200);
    }
}
