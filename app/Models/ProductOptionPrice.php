<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductOptionPrice extends Model
{
    protected $fillable = [
        'product_id', 'options', 'price',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getOptionDetails(): array
    {
        $details = [];
        foreach ($this->options as $optionId => $valueId) {
            $option = Option::find($optionId);
            $value = Value::find($valueId);

            if ($option && $value) {
                $details[] = [
                    'option' => $option->name,
                    'option_id' => $option->id,
                    'value' => $value->value,
                ];
            }
        }
        return $details;
    }
}
