<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Team\TeamStoreRequest;
use App\Http\Requests\Team\TeamUpdateRequest;
use App\Http\Resources\Team\TeamResource;
use App\Models\Team;
use Illuminate\Support\Facades\Storage;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teams = Team::orderBy('id')->get();
        return response()->json(TeamResource::collection($teams));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TeamStoreRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('teams', 'public');
            $data['image'] = $imagePath;
        }

        $team = Team::create($data);
        return response()->json(new TeamResource($team), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Team $team)
    {
        return response()->json(new TeamResource($team), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TeamUpdateRequest $request, Team $team)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($team->image) {
                Storage::disk('public')->delete('teams/' . basename($team->image));
            }
            $imagePath = $request->file('image')->store('teams', 'public');
            $data['image'] = $imagePath;
        }

        $team->update($data);
        return response()->json(new TeamResource($team), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Team $team)
    {
        $team->delete();

        if ($team->image) {
            Storage::disk('public')->delete('teams/' . basename($team->image));
        }

        return response()->json(null, 204);
    }
}
