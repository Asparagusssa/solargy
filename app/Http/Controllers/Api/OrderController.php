<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\Order;
use App\Mail\Support;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function submitForm(Request $request)
    {
        $validatedData = $request->validate([
            'items' => 'required|array',
            'items.*.name' => 'required|string',
            'items.*.price' => 'required|integer',
            'items.*.options' => 'sometimes|array',
            'items.*.options.*.name' => 'sometimes|string',
            'items.*.options.*.values' => 'sometimes|array',
            'items.*.options.*.values.*.value' => 'sometimes|string',
            'userInfo' => 'required|array',
            'userInfo.*.name' => 'required|string',
            'userInfo.*.phone' => 'required|string',
            'userInfo.*.price' => 'required|numeric',
        ]);

        Mail::to('grechapo40@yandex.ru')->send(new Order($validatedData['items'], $validatedData['userInfo']));

        return response()->json('OK', 200);
    }
}
