<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePermissionRequest extends FormRequest
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
            'name' => 'required|string|unique:permissions,name,' . $this->route('permission')->id,
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'Tên quyền không được để trống.',
            'name.unique' => 'Tên quyền này đã tồn tại. Vui lòng chọn tên khác.',
        ];
    }
}
