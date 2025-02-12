<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
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
            'name' => 'required|unique:payments,name',
            'description' => 'required|string|max:1000',
        ];
    }
    public function messages(){
        return[
            'name.required' => 'Bạn chưa nhập tên thanh toán',
            'name.unique' => 'Tên đã tồn tại, vui lòng nhập tên khác.',
            'description.required' => 'Bạn chưa nhập mô tả.',
            'description.max' => 'Mô tả không được nhập quá 1000 kí tự.',
        ];
    }
}
