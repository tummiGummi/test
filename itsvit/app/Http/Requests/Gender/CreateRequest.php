<?php

namespace App\Http\Requests\Gender;


use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:country',
        ];
    }
}
