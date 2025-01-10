<?php

namespace App\Actions;

use App\Models\Product;
use DB;

class ProductCopyAction
{
    public function __invoke($product_id)
    {
        $product = Product::find($product_id);
        if ($product == null) {
            throw new \Exception("Product not found");
        }

        DB::beginTransaction();

        try {
            $newProduct = $product->replicate();
            $newProduct->name .= ' (Копия)';
            $newProduct->save();

            foreach ($product->photos as $photo) {
                $newPhoto = $photo->replicate();
                $newPhoto->product_id = $newProduct->id;
                $newPhoto->save();
            }

            foreach ($product->values as $value) {
                $newProduct->values()->attach($value->id, ['option_id' => $value->option_id]);
            }

            foreach ($product->properties as $property) {
                $newProperty = $property->replicate();
                $newProperty->product_id = $newProduct->id;
                $newProperty->save();
            }

            foreach ($product->relatedProducts as $relatedProduct) {
                $newProduct->relatedProducts()->attach($relatedProduct->id);
            }

            DB::commit();

            return response()->json([
                'message' => 'Product copied successfully',
                'product' => $newProduct
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
