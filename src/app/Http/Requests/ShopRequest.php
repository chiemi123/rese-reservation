<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShopRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'area_id' => ['required', 'exists:areas,id'],
            'genre_id' => ['required', 'exists:genres,id'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '店舗名は必須です。',
            'area_id.required' => 'エリアを選択してください。',
            'genre_id.required' => 'ジャンルを選択してください。',
            'image.image' => '画像ファイルをアップロードしてください。',
            'image.mimes' => '画像の形式はjpeg, png, jpg, webpのみ対応しています。',
        ];
    }
}
