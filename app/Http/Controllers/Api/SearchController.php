<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\PageSection;
use App\Models\Product;
use App\Models\Promo;

class SearchController extends Controller
{
    public function search()
    {
        $q = mb_strtolower(request('q'));
        $products = Product::with([
            'photos' => function ($query) {
                $query->orderByRaw('"order" IS NULL, "order" ASC')->orderBy('id', 'ASC')->first();
            }
        ])->whereRaw('lower(name) like ?', ["%{$q}%"])
            ->orWhereRaw('lower(description) like ?', ["%{$q}%"])
            ->orWhereRaw('CAST(price AS TEXT) LIKE ?', ["%{$q}%"])
            ->orderBy('name')
            ->get();

        $newPromos = Promo::query()
            ->whereRaw('lower(title) like ?', ["%{$q}%"])
            ->orWhereRaw('lower(description) like ?', ["%{$q}%"])
            ->where('is_archived', false)
            ->orderBy('title')
            ->get();

        $archivePromos = Promo::query()
            ->whereRaw('lower(title) like ?', ["%{$q}%"])
            ->orWhereRaw('lower(description) like ?', ["%{$q}%"])
            ->where('is_archived', true)
            ->orderBy('title')
            ->get();

        $categories = Category::query()
            ->whereRaw('lower(name) like ?', ["%{$q}%"])
            ->orderBy('name')
            ->get();

        $pages = PageSection::query()
            ->whereRaw('lower(title) like ?', ["%{$q}%"])
            ->orWhereRaw('lower(html) like ?', ["%{$q}%"])
            ->orderBy('title')
            ->get();



        $results = [
            'products' => $products,
            'newPromos' => $newPromos,
            'archivePromos' => $archivePromos,
            'categories' => $categories,
            'pages' => $pages
        ];

        return response()->json($results);
    }

    public function searchFast()
    {
        $q = mb_strtolower(request('q'));
        $products = Product::with([
            'photos' => function ($query) {
                $query->orderByRaw('"order" IS NULL, "order" ASC')->orderBy('id', 'ASC')->first();
            }
        ])->whereRaw('lower(name) like ?', ["%{$q}%"])
            ->orWhereRaw('lower(description) like ?', ["%{$q}%"])
            ->orWhereRaw('CAST(price AS TEXT) LIKE ?', ["%{$q}%"])
            ->orderBy('name')
            ->get();

        $newPromos = Promo::query()
            ->whereRaw('lower(title) like ?', ["%{$q}%"])
            ->orWhereRaw('lower(description) like ?', ["%{$q}%"])
            ->where('is_archived', false)
            ->orderBy('title')
            ->get();

        $results = [
            'products' => $products,
            'newPromos' => $newPromos,
        ];

        return response()->json($results);
    }
}
