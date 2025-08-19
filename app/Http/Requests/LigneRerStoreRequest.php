<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LigneRerStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'name'      => 'required|string|max:100|unique:ligne_rers,name',
            'color'     => 'nullable|string|max:20', // ex: #FF0000
            'is_active' => 'sometimes|boolean',
        ];
    }
}
