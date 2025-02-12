<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreCinemaRequest extends FormRequest
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
            'name'          => 'required|unique:cinemas,name|min:3|max:50', 
            'address'       => 'required|string|max:150',
            'is_active'     => 'nullable|boolean', 
            'description'   => 'required|string|max:1000',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Bạn chưa nhập tên.',
            'name.unique' => 'Tên đã tồn tại.',
            'name.min' => 'Tên phải có ít nhất 3 ký tự.',
            'name.max' => 'Tên không được dài quá 50 ký tự.',
            'address.required' => 'Bạn chưa nhập địa chỉ.',
            'address.max' => 'Địa chỉ không được lớn hơn 150 ký tự.',
            'description.required' => 'Bạn chưa nhập mô tả.',
            'description.max' => 'Mô tả không được lớn hơn 1000 ký tự.',
        ];
    }
}
