<?php

namespace App\Http\Requests\Form;

use Illuminate\Foundation\Http\FormRequest;

class StoreFormRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'method' => 'required|in:GET,POST',
            'action' => 'required|string|max:255',
            'fields' => 'required|array',
            'fields.*.name' => 'required|string',
            'fields.*.type' => 'required|string|in:text,email,textarea,select',
            'fields.*.label' => 'required|string',
            'fields.*.required' => 'boolean',
            'fields.*.meta.options' => 'required_if:fields.*.type,select|array|min:1',
            'fields.*.meta.options.*' => 'required_if:fields.*.type,select|string',
        ];
    }
}
