<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'name' => 'required|min:5|max:30',
            'img_thumbnail'  => 'nullable|image|max:2048',
            'phone' => 'required|min:9|max:12|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|max:30|confirmed',
            'cinema_id' => 'required'

        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập họ và tên.',
            'name.min' => 'Họ và tên tối thiểu phải 5 ký tự.',
            'name.max' => 'Họ và tên không quá 30 ký tự.',
            'img_thumbnail.image' => 'File phải là hình ảnh.',
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'phone.min' => 'Số điện thoại tối thiểu 9 ký tự.',
            'phone.max' => 'Số điện thoại không quá 12 ký tự.',
            'phone.unique' => 'Số điện thoại đã tồn tại.',
            'email.email' => 'Email không đúng định dạng.',
            'email.required' => 'Vui lòng nhập email.',
            'email.unique' => 'Email đã tồn tại.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu tối thiểu phải 8 ký tự.',
            'password.max' => 'Mật khẩu không được quá 30 ký tự.',
            'password.confirmed' => 'Mật khẩu và xác nhận mật khẩu không trùng khớp.',
            'cinema_id.required' => 'Vui lòng nhập tên rạp quản lý.',
        ];
    }
}
