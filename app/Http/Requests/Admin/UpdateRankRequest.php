<?php

namespace App\Http\Requests\Admin;

use App\Models\Rank;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateRankRequest extends FormRequest
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
    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255|unique:ranks,name,' . $this->route('rank')->id,
            'ticket_percentage' => 'required|integer|min:0|max:20',
            'combo_percentage' => 'required|integer|min:0|max:20',
        ];

        // Nếu không phải là bản ghi mặc định, yêu cầu `total_spent`
        if (!$this->route('rank')->is_default) {
            $rules['total_spent'] = ['required', 'integer', 'min:500000','max:5000000', 'regex:/^\d*00000$/', 'unique:ranks,total_spent,' . $this->route('rank')->id];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập tên cấp bậc.',
            'name.string' => 'Tên cấp bậc phải là một chuỗi ký tự.',
            'name.max' => 'Tên cấp bậc không được vượt quá 255 ký tự.',
            'name.unique' => 'Tên cấp bậc đã tồn tại, vui lòng chọn tên khác.',
            'total_spent.required' => 'Vui lòng nhập tổng chi tiêu.',
            'total_spent.integer' => 'Tổng chi tiêu phải là một số nguyên.',
            'total_spent.min' => 'Tổng chi tiêu tối thiểu phải bằng 500.000 VNĐ.',
            'total_spent.max' => 'Tổng chi tiêu không được quá 5.000.000 VNĐ.',
            'total_spent.regex' => 'Tổng chi tiêu chia hết cho 100.000.',
            'total_spent.unique' => 'Tổng chi tiêu đã tồn tại cho cấp bậc khác.',
            'ticket_percentage.required' => 'Vui lòng nhập phần trăm tích điểm vé.',
            'ticket_percentage.integer' => 'Phần trăm tích điểm vé phải là một số nguyên.',
            'ticket_percentage.min' => 'Phần trăm tích điểm vé tối thiểu phải bằng 0%',
            'ticket_percentage.max' => 'Phần trăm tích điểm vé không được quá 20%',
            'combo_percentage.required' => 'Vui lòng nhập phần trăm tích điểm combo.',
            'combo_percentage.integer' => 'Phần trăm tích điểm combo phải là một số nguyên.',
            'combo_percentage.min' => 'Phần trăm tích điểm combo tối thiểu phải bằng 0%.',
            'combo_percentage.max' => 'Phần trăm tích điểm combo không được vượt quá 20%.',
            'ticket_percentage.greater_than_previous' => 'Phần trăm tích điểm vé phải lớn hơn cấp bậc có tổng chi tiêu thấp hơn.',
            'combo_percentage.greater_than_previous' => 'Phần trăm tích điểm combo phải lớn hơn cấp bậc có tổng chi tiêu thấp hơn.',
        ];
    }

    // public function withValidator($validator)
    // {
    //     $validator->after(function ($validator) {
    //         $maxSpentRank = Rank::orderByDesc('total_spent')->first();

    //         if ($maxSpentRank && $this->total_spent > $maxSpentRank->total_spent) {
    //             if ($this->ticket_percentage <= $maxSpentRank->ticket_percentage) {
    //                 $validator->errors()->add('ticket_percentage', 'Phần trăm tích điểm vé phải lớn hơn cấp bậc có tổng chi tiêu thấp hơn.');
    //             }

    //             if ($this->combo_percentage <= $maxSpentRank->combo_percentage) {
    //                 $validator->errors()->add('combo_percentage', 'Phần trăm tích điểm combo phải lớn hơn cấp bậc có tổng chi tiêu thấp hơn.');
    //             }
    //         }
    //     });
    // }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()
        ], 422));
    }

}
