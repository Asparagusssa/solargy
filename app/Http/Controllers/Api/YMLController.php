<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Response;
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
            if (!$product->category) continue;

            $offer = $offers->addChild('offer');
            $offer->addAttribute('id', $product->id);
            $offer->addAttribute('available', 'true');

            $offer->addChild('url', url('/product/' . $product->id));
            $offer->addChild('price', number_format($product->price, 2, '.', ''));
            $offer->addChild('currencyId', 'RUB');
            $offer->addChild('categoryId', $product->category->id);

            if ($mainPhoto = $product->photos->first()) {
                $offer->addChild('picture', $mainPhoto->photo);
            }

            $offer->addChild('name', htmlspecialchars($product->name));

            // Описание
            $description = $offer->addChild('description');
            $node = dom_import_simplexml($description);
            $owner = $node->ownerDocument;
            $node->appendChild($owner->createCDATASection($this->sanitizeText($product->description)));

            // Свойства
            foreach ($product->properties as $prop) {
                $safeName = $this->sanitizeText($prop->title);
                $safeValue = $this->sanitizeText($prop->html);
                $param = $offer->addChild('param', $safeValue);
                $param->addAttribute('name', $safeName);
            }

            foreach ($product->options as $option) {
                foreach ($option->values as $value) {
                    $valueText = $value->price > 0
                        ? $value->value . ' (+' . $value->price . ' руб.)'
                        : $value->value;

                    $param = $offer->addChild('param', $valueText);
                    $param->addAttribute('name', $this->sanitizeText($option->name));
                }
            }
        }

        return Response::make($xml->asXML(), 200)->header('Content-Type', 'application/xml');
    }

    private function sanitizeText(string $text): string
    {
        $text = str_replace('&nbsp;', ' ', $text);
        $text = strip_tags($text);
        $text = html_entity_decode($text, ENT_QUOTES | ENT_XML1, 'UTF-8');
        return trim(preg_replace('/\s+/', ' ', $text));
    }
}
