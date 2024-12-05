<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductDescription;
use Illuminate\Http\Request;

class ProductDescriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //TODO в контроллере товара или здесь делать логику вывода и редактирования
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductDescription $productDescription)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductDescription $productDescription)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductDescription $productDescription)
    {
        //
    }
}
