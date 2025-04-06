<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Shop;
use App\Models\Area;
use App\Models\Favorite;
use App\Models\Genre;
use App\Models\Review;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Shop::with(['area', 'genre']);

        // フィルター処理
        if ($request->filled('area')) {
            $query->where('area_id', $request->area);
        }

        if ($request->filled('genre')) {
            $query->where('genre_id', $request->genre);
        }

        // キーワード検索（店名・エリア名・ジャンル名にマッチ）
        if ($request->filled('word')) {
            $keyword = $request->word;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%$keyword%")
                    ->orWhereHas('area', function ($q) use ($keyword) {
                        $q->where('name', 'like', "%$keyword%");
                    })
                    ->orWhereHas('genre', function ($q) use ($keyword) {
                        $q->where('name', 'like', "%$keyword%");
                    });
            });
        }

        $shops = $query->paginate(12);

        $favorites = [];
        if (Auth::check()) {
            $favorites = Auth::user()->favorites->pluck('id')->toArray();
            // ShopモデルのIDだけ配列にして取得
        }

        return view('shops.index', [
            'shops' => $shops,
            'areas' => Area::all(),
            'genres' => Genre::all(),
            'favorites' => $favorites,
        ]);
    }

    public function show($id)
    {
        $shop = Shop::with(['area', 'genre'])->findOrFail($id);

        return view('shops.show', compact('shop'));
    }
}
