<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LigneRerUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required','string','max:100',
                Rule::unique('ligne_rers','name')->ignore($this->route('ligne_rer')->id ?? null, 'id'),
            ],
            'color'     => 'nullable|string|max:20',
            'is_active' => 'sometimes|boolean',
        ];
    }
}
