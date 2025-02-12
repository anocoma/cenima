<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactRequest extends FormRequest
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
            'user_contact' => 'required|string|min:3|max:30', // Họ và tên tối đa 30 ký tự
            'email' => 'required|email|max:50', // Email tối đa 50 ký tự
            'phone' => [
                'required',
                'regex:/^(0[3|5|7|8|9])+([0-9]{8})$/', // Số điện thoại hợp lệ Việt Nam
            ],
            'title' => 'required|string|max:255', // Tiêu đề tối đa 255 ký tự
            'content' => 'required|string|min:10|max:2000', // Nội dung từ 10 đến 2000 ký tự
        ];
    }

    public function messages()
    {
        return [         
            'user_contact.required' => 'Họ và tên là bắt buộc.',
            'user_contact.string' => 'Họ và tên phải là chuỗi ký tự.',
            'user_contact.min' => 'Họ và tên phải có ít nhất 3 ký tự.',
            'user_contact.max' => 'Họ và tên không được vượt quá 30 ký tự.',
    
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không hợp lệ.',
            'email.max' => 'Email không được vượt quá 50 ký tự.',
    
            'phone.required' => 'Số điện thoại là bắt buộc.',
            'phone.regex' => 'Số điện thoại không hợp lệ.',
    
            'title.required' => 'Tiêu đề là bắt buộc.',
            'title.string' => 'Tiêu đề phải là chuỗi ký tự.',
            'title.max' => 'Tiêu đề không được vượt quá 255 ký tự.',
    
            'content.required' => 'Nội dung là bắt buộc.',
            'content.string' => 'Nội dung phải là chuỗi ký tự.',
            'content.min' => 'Nội dung phải có ít nhất 10 ký tự.',
            'content.max' => 'Nội dung không được vượt quá 2000 ký tự.',
        ];
    }
}
