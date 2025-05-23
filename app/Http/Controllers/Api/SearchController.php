<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\News;
use App\Models\Option;
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
                $query->orderByRaw('"order" IS NULL, "order" ASC')->orderBy('id', 'ASC')->get();
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

        $news = News::query()
            ->whereRaw('lower(title) like ?', ["%{$q}%"])
            ->orWhereRaw('lower(html) like ?', ["%{$q}%"])
            ->orderBy('title')
            ->get();


        $results = [
            'products' => $products,
            'newPromos' => $newPromos,
            'archivePromos' => $archivePromos,
            'categories' => $categories,
            'pages' => $pages,
            'news' => $news
        ];

        return response()->json($results);
    }

    public function searchFast()
    {
        $q = mb_strtolower(request('q'));
        $products = Product::with([
            'photos' => function ($query) {
                $query->orderByRaw('"order" IS NULL, "order" ASC')->orderBy('id', 'ASC')->get();
            },
            'category' => function ($query) {
                $query->orderBy('id');
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

        $news = News::query()
            ->whereRaw('lower(title) like ?', ["%{$q}%"])
            ->orWhereRaw('lower(html) like ?', ["%{$q}%"])
            ->orderBy('title')
            ->limit(10)
            ->get();

        $news_count = News::query()
            ->whereRaw('lower(title) like ?', ["%{$q}%"])
            ->orWhereRaw('lower(html) like ?', ["%{$q}%"])
            ->count();

        $results = [
            'products' => $products,
            'newPromos' => $newPromos,
            'news' => $news,
            'news_count' => $news_count,
        ];

        return response()->json($results);
    }

    public function searchOptions()
    {
        $q = mb_strtolower(request('q'));
        $options = Option::with([
            'values' => function ($query) {
                $query->orderBy('id');
            },
        ])
            ->whereRaw('lower(name) like ?', ["%{$q}%"])
            ->orderBy('name')
            ->get();
        return response()->json($options);
    }
}
