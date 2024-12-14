<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\CompanyStoreRequest;
use App\Http\Requests\Company\CompanyUpdateRequest;
use App\Http\Resources\Company\CompanyResource;
use App\Models\Company;
use App\Models\CustomDetail;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Company::with('detail', 'customs')->orderBy('id')->get();

        return response()->json(CompanyResource::collection($data), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CompanyStoreRequest $request)
    {
        $data = $request->validated();


        $company = Company::create($data);

        $company->detail()->create($data['details'][0]);

        if (isset($data['custom-details'])) {
            $company->customs()->createMany($data['custom-details']);
        }

        return response()->json(new CompanyResource($company), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        return response()->json(new CompanyResource($company), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CompanyUpdateRequest $request, Company $company)
    {
        $data = $request->validated();

        $company->update($data);

        if (isset($data['details'][0])) {
            $company->detail()->update($data['details'][0]);
        }

        if (isset($data['custom-details'])) {
            for ($i = 0; $i < count($data['custom-details']); $i++) {
                if (isset($data['custom-details'][$i]['id'])) {
                    $company->customs()->where('id', $data['custom-details'][$i]['id'])->update($data['custom-details'][$i]);
                } else {
                    $company->customs()->create($data['custom-details'][$i]);
                }
            }
        }

        return response()->json(new CompanyResource($company), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        $company->delete();

        return response()->json(null, 204);
    }

    public function destroyCustom($customId)
    {
        $custom = CustomDetail::findOrFail($customId);

        $custom->delete();

        return response()->json(null, 204);
    }
}
