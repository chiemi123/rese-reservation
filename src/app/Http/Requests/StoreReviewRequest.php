<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return ture;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    public function rules(): array
    {
        return [
            'shop_id' => 'required|exists:shops,id',
            'reservation_id' => 'required|exists:reservations,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'shop_id.required' => '店舗IDは必須です。',
            'shop_id.exists' => '存在しない店舗です。',
            'reservation_id.required' => '予約IDは必須です。',
            'reservation_id.exists' => '指定された予約が存在しません。',
            'rating.required' => '評価を入力してください。',
            'rating.integer' => '評価は整数で指定してください。',
            'rating.min' => '評価は1以上で指定してください。',
            'rating.max' => '評価は5以下で指定してください。',
            'comment.required' => 'コメントを入力してください。',
            'comment.max' => 'コメントは1000文字以内で入力してください。',
        ];
    }
}
