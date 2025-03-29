<?php

namespace App\Exports;

use App\Models\Product;
use App\Models\Value;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ValueExport implements FromView
{
    protected $product;
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function view(): View
    {
        return view('excel.option', [
            'values' => Product::findOrFail($this->product->id)->values()->get()
        ]);
    }
}
