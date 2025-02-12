<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreSlideShowRequest extends FormRequest
{
    public function authorize()
    {
        // Nếu không cần kiểm tra quyền, bạn có thể trả về true
        return true;
    }

    public function rules()
    {
        return [
            'img_thumbnail.*' => 'required', // Kiểm tra từng file: là ảnh, định dạng, kích thước
            'description' => 'nullable|string|max:255', // Mô tả không bắt buộc, tối đa 255 ký tự
        ];
    }

    public function messages()
    {
        return [
            // 'img_thumbnail.*.required' => 'Ảnh không được để trống.',
            'img_thumbnail.*.image' => 'Tệp tải lên phải là một hình ảnh.',
            'description.string' => 'Mô tả phải là chuỗi ký tự.',
            'description.max' => 'Mô tả không được vượt quá :max ký tự.',
        ];
    }


    // public function rules(): array
    // {
    //     return [
    //         'img_thumbnail' => 'required|array|min:3',
    //         'img_thumbnail.*' => 'image|max:2048',
    //         'description' => 'nullable|string|max:1000',
    //     ];
    // }
    // public function messages(): array
    // {
    //     return [
    //         'img_thumbnail.required' => 'Vui lòng chọn ít nhất 3 ảnh.',
    //         'img_thumbnail.array' => 'Ảnh phải là một mảng.',
    //         'img_thumbnail.min' => 'Vui lòng chọn ít nhất 3 ảnh.',
    //         'img_thumbnail.*.image' => 'Mỗi ảnh phải có định dạng hợp lệ (jpeg, png, jpg, gif, svg).',
    //         'img_thumbnail.*.max' => 'Kích thước mỗi ảnh không được vượt quá 2MB.',
    //         'description.string' => 'Mô tả phải là một chuỗi văn bản.',
    //         'description.max' => 'Mô tả không được vượt quá 1000 ký tự.',
    //     ];
    // }
}
