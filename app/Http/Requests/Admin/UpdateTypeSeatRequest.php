<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTypeSeatRequest extends FormRequest
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
        $id = $this->route('type_seat')->id;
        return [
            'name' => 'required|unique:type_seats,name,'. $id,
            'price' => 'required|numeric|min:1',
        ];
    }
    public function messages(){
        return [
            'name.required' => 'Tên loại ghế bạn chưa nhập.',
            'name.unique' => 'Tên loại ghế đã tồn tại, vui lòng chọn tên khác.',
            'price.required' => 'Bạn chưa nhập giá.',
            'price.numeric' => 'Giá phải là số.',
            'price.min' => 'Giá phải là số lớn hơn 0.',
        ];
    }
}
