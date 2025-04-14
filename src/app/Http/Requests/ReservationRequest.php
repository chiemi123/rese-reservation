<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReservationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
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
            'date'    => 'required|date',
            'time'    => 'required',
            'number'  => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'shop_id.required' => '店舗IDが指定されていません。',
            'shop_id.exists'   => '選択された店舗が存在しません。',
            'date.required'    => '日付を選択してください。',
            'date.date'        => '日付の形式が正しくありません。',
            'time.required'    => '時間を入力してください。',
            'number.required'  => '人数を選択してください。',
            'number.integer'   => '人数は整数で入力してください。',
            'number.min'       => '1人以上を選択してください。',
        ];
    }
}
