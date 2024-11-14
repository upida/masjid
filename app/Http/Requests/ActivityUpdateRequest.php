<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActivityUpdateRequest extends FormRequest
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
        return [
            'title' => ['string', 'max:255', 'unique:activities,title,' . $this->activity->id],
            'description' => ['string', 'max:255'],
            'start_time' => ['date', 'date_format:Y-m-d H:i:s'],
            'end_time' => ['date', 'date_format:Y-m-d H:i:s'],
        ];
    }
}
