<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use SimpleXMLElement;

class YMLController extends Controller
{
    public function generate()
    {
        $categories = Category::all();
        $products = Product::with(['photos', 'options.values', 'properties', 'category'])->get();

        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><yml_catalog/>');
        $xml->addAttribute('date', Carbon::now()->toIso8601String());

        $shop = $xml->addChild('shop');
        $shop->addChild('name', 'Solargy 18');
        $shop->addChild('company', 'ООО "Solargy 18"');
        $shop->addChild('url', url('/'));

        // Валюты
        $currencies = $shop->addChild('currencies');
        $currency = $currencies->addChild('currency');
        $currency->addAttribute('id', 'RUB');
        $currency->addAttribute('rate', '1');

        // Категории
        $categoriesXml = $shop->addChild('categories');
        foreach ($categories as $category) {
            $cat = $categoriesXml->addChild('category', htmlspecialchars($category->name));
            $cat->addAttribute('id', $category->id);
        }

        // Товары
        $offers = $shop->addChild('offers');
        foreach ($products as $product) {
            // Пропускаем товары без категории
            if (!$product->category) {
                continue;
            }

            $offer = $offers->addChild('offer');
            $offer->addAttribute('id', $product->id);
            $offer->addAttribute('available', 'true');

            $offer->addChild('url', url('/product/' . $product->id));
            $offer->addChild('price', number_format($product->price, 2, '.', ''));
            $offer->addChild('currencyId', 'RUB');
            $offer->addChild('categoryId', $product->category->id);

            // Основное изображение
            if ($mainPhoto = $product->photos->first()) {
                $offer->addChild('picture', $mainPhoto->photo);
            }

            $offer->addChild('name', htmlspecialchars($product->name));

            // Описание через CDATA
            $description = $offer->addChild('description');
            $node = dom_import_simplexml($description);
            $owner = $node->ownerDocument;
            $node->appendChild($owner->createCDATASection($product->description));

            // Свойства
            foreach ($product->properties as $prop) {
                $param = $offer->addChild('param', strip_tags($prop->html));
                $param->addAttribute('name', $prop->title);
            }

            // Опции
            foreach ($product->options as $option) {
                foreach ($option->values as $value) {
                    $param = $offer->addChild('param', $value->value);
                    $param->addAttribute('name', $option->name);
                    if ($value->price > 0) {
                        $param->addAttribute('price', $value->price);
                    }
                }
            }
        }

        // Отдаём XML
        return Response::make($xml->asXML(), 200)->header('Content-Type', 'application/xml');
    }
}
