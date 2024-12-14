<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Contact\ContactStoreRequest;
use App\Http\Requests\Contact\ContactUpdateRequest;
use App\Http\Resources\Contact\ContactResource;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contacts = Contact::orderBy('id')->get();
        return response()->json(ContactResource::collection($contacts), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ContactStoreRequest $request)
    {
        $data = $request->validated();

        $contact = Contact::create($data);
        return response()->json(new ContactResource($contact), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact)
    {
        return response()->json(new ContactResource($contact), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ContactUpdateRequest $request, Contact $contact)
    {
        $data = $request->validated();

        $contact->update($data);
        return response()->json(new ContactResource($contact), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();
        return response()->json(null, 204);
    }
}
