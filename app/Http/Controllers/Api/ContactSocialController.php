<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactSocial\ContactSocialStoreRequest;
use App\Http\Requests\ContactSocial\ContactSocialUpdateRequest;
use App\Http\Resources\Contact\ContactSocialResource;
use App\Models\ContactSocial;
use Illuminate\Support\Facades\Storage;

class ContactSocialController extends Controller
{
    public function index()
    {
        $contactSocials = ContactSocial::all();

        return response()->json(ContactSocialResource::collection($contactSocials), 200);
    }

    public function store(ContactSocialStoreRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('contactSocials', 'public');
            $data['image'] = $imagePath;
        }

        $contactSocial = ContactSocial::create($data);
        return response()->json(new ContactSocialResource($contactSocial), 201);
    }

    public function show($id)
    {
        $contactSocial = ContactSocial::findOrFail($id);
        return response()->json(new ContactSocialResource($contactSocial), 200);
    }

    public function update(ContactSocialUpdateRequest $request, $id)
    {
        $data = $request->validated();

        $contactSocial = ContactSocial::findOrFail($id);

        if ($request->hasFile('image')) {
            if ($contactSocial->image) {
                Storage::disk('public')->delete('contactSocials/' . basename($contactSocial->image));
            }
            $imagePath = $request->file('image')->store('contactSocials', 'public');
            $data['image'] = $imagePath;
        }
        $contactSocial->update($data);
        return response()->json(new ContactSocialResource($contactSocial), 200);
    }

    public function destroy($id)
    {
        $contactSocial = ContactSocial::findOrFail($id);
        if ($contactSocial->image) {
            Storage::disk('public')->delete('contactSocials/' . basename($contactSocial->image));
        }
        $contactSocial->delete();

        return response()->json(null, 204);
    }
}
