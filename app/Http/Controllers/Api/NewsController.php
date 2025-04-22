<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\News\NewsStoreRequest;
use App\Http\Requests\News\NewsUpdateRequest;
use App\Http\Resources\News\NewsResource;
use App\Models\News;
use App\Models\NewsImages;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->input('type');
        $perPage = $request->input('per_page');
        $lastMonth = $request->input('last_month');

        if ($type) {
            return NewsResource::collection(News::where('type', $type)->orderBy('date', 'desc')->paginate($perPage ?? 6));
        } elseif (isset($lastMonth)) {
            return NewsResource::collection(News::where('date', '>', Carbon::now()->subMonth())->orderBy('date', 'desc')->get());
        } else {
            return NewsResource::collection(News::orderBy('date', 'desc')->paginate($perPage ?? 6));
        }
    }

    public function show($news_id)
    {
        $news = News::findOrFail($news_id);
        return response()->json(new NewsResource($news));
    }

    public function store(NewsStoreRequest $request)
    {
        $data = $request->validated();
        if (!isset($data['date'])) {
            $data['date'] = Carbon::now();
        }

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('news', 'public');
            $data['image'] = $imagePath;
        }

        $news = News::create($data);

        return response()->json(new NewsResource($news), 201);
    }

    public function update(NewsUpdateRequest $request, $news_id)
    {
        $news = News::findOrFail($news_id);

        $data = $request->validated();
        if ($request->hasFile('image')) {
            if ($news->image) {
                Storage::disk('public')->delete('news/' . basename($news->image));
            }
            $imagePath = $request->file('image')->store('news', 'public');
            $data['image'] = $imagePath;
        }
        $news->update($data);
        $news->refresh();

        return response()->json(new NewsResource($news), 200);
    }

    public function destroy($news_id)
    {
        $news = News::findOrFail($news_id);
        $news->delete();

        return response()->json(null, 204);
    }

    public function deleteImage($news_id)
    {
        $news = News::findOrFail($news_id);

        if (!$news->video) {
            return response()->json(['message' => 'Чтобы удалить фото у новости, ее видео должно быть'], 404);
        }

        if ($news->image) {
            Storage::disk('public')->delete('news/' . basename($news->image));
            $news->update(['image' => null]);
            return response()->json(null, 204);
        }
        return response()->json(['message' => 'Фото у новости не найдено'], 404);
    }
}
