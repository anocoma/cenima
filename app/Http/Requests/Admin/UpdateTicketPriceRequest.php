<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTicketPriceRequest extends FormRequest
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
            'prices.*' => ['nullable', 'numeric', 'min:0'],
            'surcharges.*' => ['nullable', 'numeric', 'min:0'],
            'surchargesCinema.*' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function messages()
    {
        return [
            'prices.*.numeric' => 'Giá theo ghế phải là số.',
            'prices.*.min' => 'Giá theo ghế không được nhỏ hơn 0.',
            'surcharges.*.numeric' => 'Giá theo loại phòng phải là số.',
            'surcharges.*.min' => 'Giá theo loại phòng không được nhỏ hơn 0.',
            'surchargesCinema.*.numeric' => 'Giá rạp phải là số.',
            'surchargesCinema.*.min' => 'Giá rạp không được nhỏ hơn 0.',
        ];
    }
}
