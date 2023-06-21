<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'content' => ['required', 'string'],
            'chat_id' => ['required', 'string'],
            // file can be null or image
            'file' => ['nullable', 'mimes:png,jpg,jpeg', 'max:2048'],
            'user_id' => ['required', 'numeric'],
        ];
    }
}
