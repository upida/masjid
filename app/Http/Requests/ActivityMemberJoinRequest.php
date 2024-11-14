<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActivityMemberJoinRequest extends FormRequest
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
            'code' => 'required_if:user_id,null|exists:qrcodes,code',
            'user_id' => 'required_if:code,null|exists:users,id',
            'status' => 'in:present,permit',
            'reason' => 'required_if:status,permit',
        ];
    }
}
