<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Email\EmailTypeStoreRequest;
use App\Http\Requests\Email\EmailTypeUpdateRequest;
use App\Http\Resources\Email\EmailTypeResource;
use App\Models\EmailType;
use Illuminate\Http\Request;

class EmailTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = EmailType::with('emails')->get();

        return response()->json(EmailTypeResource::collection($data));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmailTypeStoreRequest $request)
    {
        $request->validated();
        $type = EmailType::create($request->only('type'));
        return response()->json(new EmailTypeResource($type));
    }

    /**
     * Display the specified resource.
     */
    public function show($mailTypeId)
    {
        return response()->json(new EmailTypeResource(EmailType::findOrFail($mailTypeId)->load('emails')));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmailTypeUpdateRequest $request, $mailTypeId)
    {
        $request->validated();
        $type = EmailType::findOrFail($mailTypeId);
        if ($request->has('type')) {
            $type->update($request->only('type'));
        }
        if ($request->has('email_id')) {
            $type->emails()->sync($request->email_id);
        }
        return response()->json(new EmailTypeResource($type->load('emails')));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($mailTypeId)
    {
        $type = EmailType::findOrFail($mailTypeId);
        $type->delete();

        return response()->json(null, 204);
    }

    public function destroyPivot($mailTypeId, $mailId)
    {
        $type = EmailType::findOrFail($mailTypeId);
        $type->emails()->detach($mailId);
        return response()->json(null, 204);
    }
}
