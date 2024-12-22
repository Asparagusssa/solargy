<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\Order;
use App\Mail\Support;
use App\Models\EmailType;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Storage;

class OrderController extends Controller
{
    public function submitForm(Request $request)
    {
        $validatedData = $request->validate([
            'items' => 'required|array',
            'items.*.name' => 'required|string',
            'items.*.photo' => 'required|string',
            'items.*.price' => 'required|numeric',
            'items.*.quantity' => 'sometimes|required|numeric',
            'items.*.options' => 'sometimes|array',
            'items.*.options.*.name' => 'sometimes|string',
            'items.*.options.*.values' => 'sometimes|array',
            'items.*.options.*.values.*.value' => 'sometimes|string',
            'userInfo' => 'required|array',
            'userInfo.*.name' => 'required|string',
            'userInfo.*.phone' => 'required|string',
            'userInfo.*.price' => 'required|numeric',
            'email-type' => 'required|exists:email_types,id',
        ]);

        $type = EmailType::findOrFail($request->input('email-type'));

        $emailAddresses = $type->emails->pluck('email')->toArray();

        Mail::to($emailAddresses)->send(new Order($validatedData['items'], $validatedData['userInfo']));

        return response()->json('OK', 200);
    }
}
