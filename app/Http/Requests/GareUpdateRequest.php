<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GareUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required','string','max:150',
                Rule::unique('gares','name')->ignore($this->route('gare')->id ?? null, 'id'),
            ],
            'ligne_rer_id' => 'required|integer|exists:ligne_rers,id',
        ];
    }
}
