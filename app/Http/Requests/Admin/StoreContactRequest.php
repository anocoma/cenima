<?php

namespace App\Http\Requests\Admin;

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
            'user_contact' => 'required', // Bắt buộc phải có trường user_contact
            'email' => 'required|email', // Bắt buộc và phải là định dạng email
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10', // Bắt buộc, phải là số điện thoại hợp lệ
            'title' => 'required|string|max:255', // Bắt buộc, là chuỗi và tối đa 255 ký tự
            'content' => 'required|string', // Bắt buộc, phải là chuỗi
        ];
    }
    public function messages(){
        return[
            'user_contact.required' => 'Trường liên hệ người dùng là bắt buộc.',
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Địa chỉ email không hợp lệ.',
            'phone.required' => 'Số điện thoại là bắt buộc.',
            'phone.regex' => 'Số điện thoại không hợp lệ.',
            'phone.min' => 'Số điện thoại phải có ít nhất 10 ký tự.',
            'title.required' => 'Tiêu đề là bắt buộc.',
            'title.string' => 'Tiêu đề phải là một chuỗi.',
            'title.max' => 'Tiêu đề không được vượt quá 255 ký tự.',
            'content.required' => 'Nội dung là bắt buộc.',
            'content.string' => 'Nội dung phải là một chuỗi.',
        ];
    }
}
