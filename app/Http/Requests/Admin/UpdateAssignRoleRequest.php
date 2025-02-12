<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAssignRoleRequest extends FormRequest
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
            'role_id' => 'nullable|array', // Cho phép trống khi không có vai trò nào được chọn
            
        ];
    }

    public function messages(): array
    {
        return [
            'role_id.array' => 'Vai trò phải là một mảng giá trị hợp lệ.',
            
        ];
    }
}
