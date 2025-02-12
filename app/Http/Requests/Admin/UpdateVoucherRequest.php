<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVoucherRequest extends FormRequest
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
        $voucherId = $this->route('voucher');

        return [
            'code' => 'required|string|max:30|min:6|unique:vouchers,code,' . $voucherId,
            'title' => 'required|string|max:255',
            'start_date_time' => 'required|date|before:end_date_time',
            'end_date_time' => 'required|date|after:start_date_time',
            'discount' => ['required', 'regex:/^\d+(\.\d{1,2})?%?$/'],
            'quantity' => 'required|integer|min:1',
            'limit' => 'required|integer|min:1',
        ];
    }

    public function messages()
    {
        return [
            'code.required' => 'Mã là bắt buộc.',
            'code.string' => 'Mã phải là kiểu chuỗi.',
            'code.unique' => 'Mã này đã tồn tại.',
            'code.max' => 'Mã không được dài quá 30 ký tự.',
            'code.min' => 'Mã không được ngắn hơn 6 ký tự.',

            'title.required' => 'Tiêu đề là bắt buộc.',
            'title.string' => 'Tiêu đề phải là kiểu chuỗi.',
            'title.max' => 'Tiêu đề không được dài quá 255 ký tự.',

            'start_date_time.required' => 'Thời gian bắt đầu là bắt buộc.',
            'start_date_time.date' => 'Thời gian bắt đầu phải là một ngày hợp lệ.',
            'start_date_time.before' => 'Thời gian bắt đầu phải trước thời gian kết thúc.',

            'end_date_time.required' => 'Thời gian kết thúc là bắt buộc.',
            'end_date_time.date' => 'Thời gian kết thúc phải là một ngày hợp lệ.',
            'end_date_time.after' => 'Thời gian kết thúc phải sau thời gian bắt đầu.',

            'discount.required' => 'Giảm giá là bắt buộc.',
            'discount.regex' => 'Giảm giá phải là số nguyên hoặc phần trăm hợp lệ.',

            'quantity.required' => 'Số lượng là bắt buộc.',
            'quantity.integer' => 'Số lượng phải là số nguyên.',
            'quantity.min' => 'Số lượng phải lớn hơn hoặc bằng 1.',

            'limit.required' => 'Giới hạn là bắt buộc.',
            'limit.integer' => 'Giới hạn phải là số nguyên.',
            'limit.min' => 'Giới hạn phải lớn hơn hoặc bằng 1.',
        ];
    }
}
