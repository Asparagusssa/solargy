<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\Support;
use App\Models\EmailType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SupportController extends Controller
{
    public function submitForm(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'comment' => 'sometimes',
            'email-type' => 'required|exists:email_types,id',
        ]);

        $type = EmailType::findOrFail($request->input('email-type'));

        $emailAddresses = $type->emails->pluck('email')->toArray();

        Mail::to($emailAddresses)->send(new Support(['name' => $validatedData['name'], 'email' => $validatedData['email'], 'comment' => $validatedData['comment']]));

        return response()->json('OK', 200);
    }
}
