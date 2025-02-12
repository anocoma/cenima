<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreVoucherRequest extends FormRequest
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
            'code' => 'required|string|max:30|min:6|unique:vouchers,code',
            'title' => 'required|string|min:3|max:255',
            'start_date_time' => 'required|date|before_or_equal:end_date_time',
            'end_date_time' => 'required|date|after:start_date_time|after_or_equal:now',
            'discount' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'limit' => 'nullable|integer|min:1',
            'description' => 'nullable|string|max:1000',
        ];
    }
    public function messages()
    {
        return [
            'code.required' => 'Mã voucher là bắt buộc.',
            'code.string' => 'Mã voucher phải là một chuỗi ký tự.',
            'code.unique' => 'Mã voucher này đã tồn tại, vui lòng chọn mã khác.',
            'code.max' => 'Mã không được dài quá 30 ký tự.',
            'code.min' => 'Mã không được ngắn hơn 6 ký tự.',

            'title.required' => 'Tiêu đề là bắt buộc.',
            'title.string' => 'Tiêu đề phải là một chuỗi ký tự.',
            'title.min' => 'Tiêu đề phải có ít nhất 3 ký tự.',
            'title.max' => 'Tiêu đề không được vượt quá 255 ký tự.',

            'start_date_time.required' => 'Vui lòng chọn ngày bắt đầu.',
            'start_date_time.date' => 'Ngày bắt đầu phải là ngày hợp lệ.',
            'start_date_time.before_or_equal' => 'Ngày bắt đầu phải trước hoặc bằng ngày kết thúc.',

            'end_date_time.required' => 'Vui lòng chọn ngày kết thúc.',
            'end_date_time.date' => 'Ngày kết thúc phải là ngày hợp lệ.',
            'end_date_time.after' => 'Ngày kết thúc phải sau ngày bắt đầu.',
            'end_date_time.after_or_equal' => 'Ngày kết thúc không thể là thời gian trong quá khứ.',

            'discount.required' => 'Vui lòng nhập số tiền giảm giá.',
            'discount.numeric' => 'Số tiền giảm giá phải là số.',
            'discount.min' => 'Số tiền giảm giá phải lớn hơn hoặc bằng 0.',

            'quantity.required' => 'Số lượng voucher là bắt buộc.',
            'quantity.integer' => 'Số lượng voucher phải là số nguyên.',
            'quantity.min' => 'Số lượng voucher phải lớn hơn hoặc bằng 1.',

            'limit.integer' => 'Giới hạn sử dụng phải là số nguyên.',
            'limit.min' => 'Giới hạn sử dụng phải lớn hơn hoặc bằng 1 nếu được đặt.',

            'description.string' => 'Mô tả phải là một chuỗi ký tự.',
            'description.max' => 'Mô tả không được vượt quá :max ký tự.',

        ];
    }
}
