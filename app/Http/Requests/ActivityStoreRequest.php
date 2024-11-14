<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActivityStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $this->merge([
            'user_id' => $this->user()->id,
        ]);

        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['string', 'max:255'],
            'start_time' => ['required', 'date', 'date_format:Y-m-d H:i:s'],
            'end_time' => ['date', 'date_format:Y-m-d H:i:s'],
        ];
    }
}
