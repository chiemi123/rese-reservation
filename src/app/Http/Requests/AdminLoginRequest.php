<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminLoginRequest extends FormRequest
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
    public function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required|min:12',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => '管理者のメールアドレスは必須です。',
            'password.required' => 'パスワードは必須です。',
            'password.min' => 'パスワードは12文字以上にしてください。',
        ];
    }
}
