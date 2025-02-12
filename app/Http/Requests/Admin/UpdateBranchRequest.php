<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateBranchRequest extends FormRequest
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
        $id = $this->route('branch')->id;
        return [
            'name' => 'required|unique:branches,name,' . $id . '|min:3|max:50',
        ];
    }

    public function messages(): array{
        return [
            'name.required' => 'Vui lòng nhập tên chi nhánh.',
            'name.unique' => 'Tên chi nhánh đã tồn tại, vui lòng chọn tên khác.',
            'name.min' => 'Tên chi nhánh phải tối thiểu phải có 3 ký tự.',
            'name.max' => 'Tên chi nhánh không được vượt quá 50 ký tự.',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()
        ], 422));
    }
}
