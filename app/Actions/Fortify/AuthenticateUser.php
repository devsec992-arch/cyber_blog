<?php
namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Http\Requests\LoginRequest;

class AuthenticateUser
{
    public function __invoke(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $user = \App\Models\User::where('email', $credentials['email'])->first();

        if ($user) {
            $pepper = config('app.pepper');
            $passwordWithPepper = $credentials['password'] . $pepper . $user->salt;
            dd($passwordWithPepper);

            if (Hash::check($passwordWithPepper, $user->password)) {
                Auth::login($user);
                return $user;
            }
        }

        return null;
    }
}