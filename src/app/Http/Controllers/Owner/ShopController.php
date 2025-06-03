<?php

namespace App\Http\Controllers\Owner;

use App\Models\Shop;
use App\Models\Area;
use App\Models\Genre;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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

        if ($request->hasFile('image')) {
            $disk = config('filesystems.default'); // local or s3
            $path = $request->file('image')->store('shops', $disk);

            if ($disk === 's3') {
                // S3: 完全URLを取得
                $shop->image = Storage::disk($disk)->url($path);
            } else {
                // ローカル: パスを表示用に変換
                $shop->image = 'storage/' . $path;
            }
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
            $disk = config('filesystems.default'); // local or s3
            $path = $request->file('image')->store('shops', $disk);

            if ($disk === 's3') {
                // S3: 完全URLを取得
                $shop->image = Storage::disk($disk)->url($path);
            } else {
                // ローカル: パスを表示用に変換
                $shop->image = 'storage/' . $path;
            }
        }

        $shop->save();

        return redirect()->route('owner.dashboard')->with('success', '店舗情報を更新しました');
    }
}
