<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateUserRequest extends FormRequest
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
    // public function rules(): array
    // {
    //     $id = Auth::user()->id;
    //     return [
    //         'name' => 'required|min:5|max:30',
    //         'img_thumbnail'  => 'nullable|image|max:2048',
    //         'phone' => 'required|min:9|max:12|unique:users,phone, ' . $id,
    //         'birthday' => [
    //             'required',
    //             'date',
    //             'before:' . now()->subYears(10)->toDateString(),  // Phải đủ 10 tuổi
    //             'after_or_equal:' . now()->subYears(70)->toDateString(),  // Không quá 70 tuổi
    //         ],
    //     ];
    // }

    public function rules(): array
    {
        $user = Auth::user(); // Lấy thông tin user hiện tại

        // Quy tắc mặc định
        $rules = [
            'name' => 'required|min:5|max:30',
            'img_thumbnail' => 'nullable|image|max:2048',
            'phone' => 'required|min:9|max:12|unique:users,phone,' . $user->id,
        ];

        // Chỉ cho phép cập nhật 'birthday' nếu birthday hiện tại là null
        if ($user->birthday === null) {
            $rules['birthday'] = [
                'required',
                'date',
                'before:' . now()->subYears(10)->toDateString(), // Phải đủ 10 tuổi
                'after_or_equal:' . now()->subYears(70)->toDateString(), // Không quá 70 tuổi
            ];
        }

        return $rules;
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
            'phone.unique' => 'Số điện thoại này đã được sử dụng.',
            'birthday.required' => 'Vui lòng nhập ngày sinh.',
            'birthday.date' => 'Ngày sinh phải là một ngày hợp lệ.',
            'birthday.before' => 'Bạn phải đủ trên 10 tuổi để đăng ký.',
            'birthday.after_or_equal' => 'Ngày sinh không được quá 70 năm trước.',
        ];
    }
}
