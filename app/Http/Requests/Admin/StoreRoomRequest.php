<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRoomRequest extends FormRequest
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
        // $cinemaId = $this->route('cinema'); // hoặc lấy từ dữ liệu request nếu không sử dụng route model binding

        // return [
            // 'name' => [
            //     'required',
            //     Rule::unique('rooms')
            //         ->where(function ($query) use ($cinemaId) {
            //             return $query->where('cinema_id', $cinemaId);
            //         }),
            // ],
        //     // Các quy tắc validate khác
        // ];
        $cinemaId = $this->branch_id;
        return [
            'name' => [
                'required',
                Rule::unique('rooms')
                    ->where(function ($query) use ($cinemaId) {
                        return $query->where('cinema_id', $cinemaId);
                    }),
            ],
            'branch_id' => 'required|integer',
            'cinema_id' => 'required|integer',
            'type_room_id' => 'required|integer',
        ];

    }

    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập tên phòng chiếu.',
            'name.unique' => 'Tên phòng đã tồn tại trong rạp.',
            'branch_id.required' => "Vui lòng chọn chi nhánh.",
            'cinema_id.required' => "Vui lòng chọn rạp chiếu.",
            'type_room_id.required' => "Vui lòng chọn loại phòng.",
        ];
    }
}
