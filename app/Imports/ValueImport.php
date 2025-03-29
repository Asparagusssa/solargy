<?php

namespace App\Imports;

use App\Models\Option;
use App\Models\Product;
use App\Models\Value;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ValueImport implements ToCollection, WithStartRow
{
    protected $product;
    public function __construct(Product $product)
    {
        $this->product = $product;
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
//    public function model(array $row)
//    {
//        $value = Value::findOrFail((int) $row['id'])->first();
//        $option = Option::findOrFail((int) $row['option_id'])->first();
//
//        if ($value) {
//            $this->product->values()->attach($value->id, ['option_id' => $option->id]);
//        }
//    }

    public function collection(Collection $collection)
    {
        foreach ($collection as $row)
        {
            $value = Value::findOrFail($row[0]);
            $option = Option::findOrFail($row[1])->first();
            if ($value && $this->product->values()->where('value_id', $value->id)->count() == 0) {
                $this->product->values()->attach($value->id, ['option_id' => $option->id]);
            }
        }
        return null;
    }

    public function startRow(): int
    {
        return 2;
    }
}
