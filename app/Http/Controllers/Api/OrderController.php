<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\Order;
use App\Mail\ClientOrder;
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
            'items.*.url' => 'required|url',
            'items.*.photo' => 'sometimes',
            'items.*.price' => 'required|numeric',
            'items.*.quantity' => 'numeric',
            'items.*.options' => 'nullable|array',
            'items.*.options.*.name' => 'nullable|string',
            'items.*.options.*.values' => 'nullable|array',
            'items.*.options.*.values.*.value' => 'nullable|string',
            'userInfo' => 'required|array',
            'userInfo.*.name' => 'required|string',
            'userInfo.*.phone' => 'required|string',
            'userInfo.*.price' => 'required|numeric',
            'userInfo.*.email' => 'required|email',
            'keoInfo' => 'array',
            'keoInfo.*.city' => 'string|nullable',
            'keoInfo.*.company_name' => 'string|nullable',
            'keoInfo.*.job_title' => 'string|nullable',
            'keoInfo.*.object_address' => 'string|nullable',
            'keoInfo.*.contact_method' => 'string|nullable|in:telegram,whatsapp,phone,email',
            'keoInfo.*.request_features' => 'array',
            'keoInfo.*.request_features.*' => 'string|nullable|in:before_expertise,after_expertise,keo_calc,system_selection',
            'keoInfo.*.description' => 'string|nullable',
            'attachment'   => 'sometimes|array',
            'attachment.*' => 'file|mimes:pdf,doc,docx,png,jpg,jpeg,zip|max:51200',
            'email-type' => 'required|exists:email_types,id',
        ]);


        $items = $validatedData['items'];
        foreach ($items as &$item) {
            $item['options'] = $item['options'] ?? [];
        }
        unset($item);


        $keo = null;
        if (!empty($validatedData['keoInfo']) && is_array($validatedData['keoInfo'])) {
            $keo = $validatedData['keoInfo'][0];
        }


        $storedAttachments = [];

        if ($request->hasFile('attachment')) {
            $files = $request->file('attachment');
            $files = is_array($files) ? $files : [$files];

            foreach ($files as $file) {
                $path = $file->store('orders/tmp', 'public');

                $storedAttachments[] = [
                    'disk' => 'public',
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                ];
            }
        }


        // отправка менеджеру
        $type = EmailType::findOrFail($request->input('email-type'));
        $emailAddresses = $type->emails->pluck('email')->toArray();

        // отправка клиенту
        $user = $validatedData['userInfo'][0] ?? $validatedData['userInfo'];
        $clientEmail = $user['email'];


        try {
            // 1) менеджеру
            Mail::to($emailAddresses)->send(
                new Order($items, $validatedData['userInfo'], $keo, $storedAttachments)
            );

            // 2) клиенту
            Mail::to($user['email'])->send(
                new ClientOrder($items, $user)
            );

        } finally {
            // удаляем временные файлы всегда
            foreach ($storedAttachments as $f) {
                if (!empty($f['path'])) {
                    Storage::disk($f['disk'] ?? 'public')->delete($f['path']);
                }
            }
        }

        return response()->json('OK', 200);
    }
}
