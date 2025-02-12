<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;


class GoogleAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }
    public function callBackGoogle()
    {
        try {
            $userGoogle = Socialite::driver('google')->user();
            $user = User::where('service_id', $userGoogle->id)
                ->where('service_name', 'google')
                ->first();


            if ($user) {
                Auth::login($user);
                return redirect()->intended('/');
            } else {
                $existUser = User::where('email', $userGoogle->email)->first();
                if ($existUser) {
                    $existUser->update([
                        'service_id' => $userGoogle->id,
                        'service_name' => 'google',
                    ]);
                    Auth::login($existUser);
                } else {
                    $newUser =  User::create([
                        'name' => $userGoogle->name,
                        'email' => $userGoogle->email,
                        'password' => encrypt(Str::random(10)),
                        'service_id' => $userGoogle->id,
                        'service_name' => 'google',
                    ]);
                    Membership::create([
                        'user_id' => $newUser->id ,
                        'code' => Membership::codeMembership(),
                    ]);
                    Auth::login($newUser);
                }

                return redirect()->intended('/');
            }
        } catch (Exception $e) {
            dd($e->getMessage());
            // return redirect()->intended('http://datn-hn5.test');
        }
    }
}
