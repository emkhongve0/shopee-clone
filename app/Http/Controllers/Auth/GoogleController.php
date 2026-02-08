<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Exception;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('google_id', $googleUser->id)
                        ->orWhere('email', $googleUser->email)
                        ->first();

            if ($user) {
                $user->update(['google_id' => $googleUser->id]);
                Auth::login($user);
            } else {
                $user = User::create([
                    'name'      => $googleUser->name,
                    'email'     => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'password'  => bcrypt(Str::random(16)),
                ]);
                Auth::login($user);
            }

            return redirect()->intended('/')->with('success', 'Đăng nhập Google thành công!');

        } catch (Exception $e) {
            return redirect('/login')->with('error', 'Đăng nhập Google thất bại.');
        }
    }
}
