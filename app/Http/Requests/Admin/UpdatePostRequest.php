<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
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
        $id = $this->route('post')->id; // Lấy ID của bài viết đang cập nhật
        return [
            'title' => 'required|max:255|unique:posts,title,' . $id,
            'img_post' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'required|max:500',
            'content' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'title.required' => 'Tiêu đề không được để trống.',
            'title.unique' => 'Tiêu đề bài viết này đã tồn tại.',
            'title.max' => 'Tiêu đề không được vượt quá 255 ký tự.',
            'img_post.image' => 'File phải là một hình ảnh.',
            'img_post.mimes' => 'Chỉ chấp nhận các định dạng: jpeg, png, jpg, gif, svg.',
            'img_post.max' => 'Hình ảnh không được lớn hơn 2MB.',
            'description.required' => 'Mô tả ngắn không được để trống.',
            'description.max' => 'Mô tả không được vượt quá 500 ký tự.',
            'content.required' => 'Nội dung không được để trống.',
        ];
    }
}
