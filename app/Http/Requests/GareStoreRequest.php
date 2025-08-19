<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GareStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'name'         => 'required|string|max:150|unique:gares,name',
            'ligne_rer_id' => 'required|integer|exists:ligne_rers,id',
        ];
    }
}
