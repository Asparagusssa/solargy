<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Email\EmailStoreRequest;
use App\Http\Requests\Email\EmailUpdateRequest;
use App\Http\Resources\Email\EmailResource;
use App\Models\Email;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Email::orderBy('id')->get();
        return response()->json(EmailResource::collection($data));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmailStoreRequest $request)
    {
        $request->validated();
        return response()->json(EmailResource::make(Email::create($request->only('email'))));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return response()->json(new EmailResource(Email::findOrFail($id)));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmailUpdateRequest $request, string $id)
    {
        $request->validated();
        $email = Email::findOrFail($id);
        $email->update($request->only('email'));

        return response()->json(new EmailResource($email));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $email = Email::findOrFail($id);
        $email->delete();

        return response()->json(null, 204);
    }
}
