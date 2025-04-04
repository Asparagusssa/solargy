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
        $contactSocials = ContactSocial::orderBy('id')->get();

        return response()->json(ContactSocialResource::collection($contactSocials), 200);
    }

    public function store(ContactSocialStoreRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('contactSocials', 'public');
            $data['image'] = $imagePath;
        }

        if($request->hasFile('image_footer')) {
            $imagePath = $request->file('image_footer')->store('contactSocials', 'public');
            $data['image_footer'] = $imagePath;
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

        if($request->hasFile('image_footer')) {
            if ($contactSocial->image_footer) {
                Storage::disk('public')->delete('contactSocials/' . basename($contactSocial->image_footer));
            }
            $imagePath = $request->file('image_footer')->store('contactSocials', 'public');
            $data['image_footer'] = $imagePath;
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
        if ($contactSocial->image_footer) {
            Storage::disk('public')->delete('contactSocials/' . basename($contactSocial->image_footer));
        }
        $contactSocial->delete();

        return response()->json(null, 204);
    }
}
