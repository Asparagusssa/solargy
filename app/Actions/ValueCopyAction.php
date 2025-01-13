<?php

namespace App\Actions;

use App\Models\Value;
use DB;

class ValueCopyAction
{
    public function __invoke($option_value_id)
    {
        $value = Value::find($option_value_id);
        if ($value == null) {
            throw new \Exception("Product not found");
        }
        DB::beginTransaction();

        try {
            $newValue = $value->replicate();
            $newValue->value .= ' (Копия)';
            $newValue->save();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        DB::commit();

        return response()->json([
            'message' => 'Value copied successfully',
            'value' => $newValue
        ], 201);
    }
}
