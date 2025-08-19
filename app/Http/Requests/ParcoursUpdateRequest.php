<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ParcoursUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Seuls les admins peuvent modifier un CRIV
        return $this->user() && $this->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('parcours', 'name')->ignore($this->route('parcours')->id ?? null),
            ],
            'is_active' => 'sometimes|boolean',
        ];
    }
}
