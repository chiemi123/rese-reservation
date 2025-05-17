<?php

namespace App\Http\Controllers\Owner;

use App\Models\Shop;
use App\Models\Area;
use App\Models\Genre;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ShopRequest;


class ShopController extends Controller
{
    // 登録フォーム表示
    public function create()
    {
        $areas = Area::all();
        $genres = Genre::all();
        return view('owner.shops.form', compact('areas', 'genres'));
    }

    // 登録処理
    public function store(ShopRequest $request)
    {
        $shop = new Shop();
        $shop->fill($request->validated());
        $shop->owner_id = auth()->id(); // オーナーのIDを保存

        // 画像が送信されていれば保存処理を追加
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('shops', 'public');
            $shop->image = $imagePath;
        }

        $shop->save();



        return redirect()->route('owner.dashboard')->with('success', '店舗を登録しました');
    }

    // 編集フォーム表示
    public function edit(Shop $shop)
    {
        $areas = Area::all();
        $genres = Genre::all();
        return view('owner.shops.form', compact('shop', 'areas', 'genres'));
    }

    // 更新処理
    public function update(ShopRequest $request, Shop $shop)
    {
        $shop->fill($request->validated());

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('shops', 'public');
            $shop->image = $imagePath;
        }

        $shop->save();

        return redirect()->route('owner.shops.index')->with('message', '店舗情報を更新しました');
    }
}
