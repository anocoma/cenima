<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreComboRequest extends FormRequest
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
            'name'          => 'required|unique:combos,name',
            'img_thumbnail' => 'required|image|max:2048',
            'price_sale'    => 'required|numeric|min:1000|max:1000000',
            'is_active'     => 'nullable|boolean',
            'description'   => 'required|string|max:1000',
            'combo_food.*'  => 'required|exists:food,id',  // Đảm bảo đồ ăn được chọn có tồn tại trong DB
            'combo_quantity.*' => 'required|integer|min:1|max:9'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Bạn chưa nhập tên.',
            'name.unique' => 'Tên đã tồn tại.',
            'img_thumbnail.required' => 'Bạn chưa thêm ảnh.',
            'img_thumbnail.image' => 'File phải là một hình ảnh.',
            'price_sale.required' => 'Bạn chưa nhập giá.',
            'price_sale.numeric' => 'Giá phải là số.',
            'price_sale.min' => 'Giá phải lớn hơn 1000đ.',
            'price_sale.max' => 'Giá phải nhỏ hơn 1.000.000đ.',
            'description.required' => 'Bạn chưa nhập mô tả.',
            'combo_food.*.required' => 'Bạn chưa chọn món.',
            'combo_food.*.exists' => 'Món ăn bạn chọn không tồn tại.',
            'combo_quantity.*.integer' => 'Số lượng phải là số nguyên.',
            'combo_quantity.*.min' => 'Số lượng phải lớn hơn 0.',
            'combo_quantity.*.max' => 'Số lượng phải nhỏ hơn 9.',
        ];
    }
}
