<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTypeRoomRequest extends FormRequest
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
        $id = $this->route('typeRoom')->id;     //số ít
        return [
            //
            'name' => 'required|unique:type_rooms,name,' . $id,
            'surcharge' => 'required|numeric|min:-1',
        ];
    }
    public function messages()
    {
        return [
            'name.required' => "Vui lòng nhập tên.",
            'name.unique' => "Tên đã tồn tại.",
            'surcharge.required' => "Vui lòng nhập phụ phí.",
            'surcharge.numeric' => "Phụ phí phải là số.",
            'surcharge.min' => "Phụ phí phải là số lớn hơn hoặc bằng 0."
        ];
    }
}
