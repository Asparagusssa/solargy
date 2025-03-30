<?php

namespace App\Exports;

use App\Models\Option;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class OptionExport implements FromView
{
    private $option;
    public function __construct(Option $option)
    {
        $this->option = $option;
    }

    public function view(): View
    {
        return view('excel.optionValues', [
            'option' => Option::with('values')->findOrFail($this->option->id)
        ]);
    }
}
