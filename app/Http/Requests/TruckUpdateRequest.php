<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TruckUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'plat_no' => 'required|unique:trucks,plat_no,',
            'brand_truk' => 'required',
            'no_stnk' => 'required',
            'no_kir' => 'required',
            'no_pajak' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }
}
