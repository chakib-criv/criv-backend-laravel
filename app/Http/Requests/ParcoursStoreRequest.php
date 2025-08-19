<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ParcoursStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Seuls les admins peuvent créer un CRIV
        return $this->user() && $this->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100|unique:parcours,name',
            'is_active' => 'sometimes|boolean',
        ];
    }
}
