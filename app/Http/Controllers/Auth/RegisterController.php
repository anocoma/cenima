<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'min:2', 'max:30'],
            'email' => ['required', 'string', 'email', 'max:30', 'unique:users'],
            'birthday' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    $birthday = \Carbon\Carbon::parse($value);
                    $currentYear = \Carbon\Carbon::now()->year;

                    // Kiểm tra năm sinh > 1900
                    if ($birthday->year <= 1900) {
                        $fail('Năm sinh phải lớn hơn 1900.');
                    }

                    // Kiểm tra tuổi phải trên 12
                    $age = $birthday->age;
                    if ($age < 12) {
                        $fail('Bạn phải lớn hơn 12 tuổi.');
                    }
                },
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => 'Bạn chưa nhập tên.',
            'name.string' => 'Tên phải là một chuỗi ký tự.',
            'name.min' => 'Tên phải có ít nhất 2 ký tự.',
            'name.max' => 'Tên không được quá 30 ký tự.',

            'email.required' => 'Bạn chưa nhập email.',
            'email.string' => 'Email phải là một chuỗi ký tự.',
            'email.email' => 'Địa chỉ email không hợp lệ.',
            'email.max' => 'Email không được quá 30 ký tự.',
            'email.unique' => 'Email này đã được đăng ký, vui lòng chọn email khác.',

            'birthday.required' => 'Bạn chưa nhập ngày sinh.',
            'birthday.date' => 'Ngày sinh phải ở định dạng hợp lệ.',

            'password.required' => 'Bạn chưa nhập mật khẩu.',
            'password.string' => 'Mật khẩu phải là một chuỗi ký tự.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $user =  User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'birthday' => $data['birthday'],
            'password' => Hash::make($data['password']),
        ]);
        Membership::create([
            'user_id' => $user->id,
            'code' => Membership::codeMembership(),
        ]);
        return $user;
    }
}
