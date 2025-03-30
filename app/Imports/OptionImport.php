<?php

namespace App\Imports;

use App\Models\Option;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class OptionImport implements ToCollection, WithStartRow
{
    public function collection(Collection $collection)
    {
        $option = Option::create([
            'name' => $collection[0][0]
        ]);
        foreach ($collection as $row)
        {
            $option->values()->create([
                'value' => $row[1],
                'price' => $row[2],
                'order' => $row[3],
            ]);
        }
        return null;
    }

    public function startRow(): int
    {
        return 2;
    }
}
