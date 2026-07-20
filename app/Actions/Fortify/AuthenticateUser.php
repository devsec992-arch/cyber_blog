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
        $user = User::where('email', $credentials['email'])->first();

        if ($user) {
            $pepper = config('app.pepper');
            $passwordWithPepper = $credentials['password'] . $pepper . $user->salt;
            //dd($passwordWithPepper);

            if (Hash::check($passwordWithPepper, $user->password)) {
                Auth::login($user);
                return $user;
            }
        }

        return null;
    }
}

/*
class AuthenticateUser
{
    public function __invoke(LoginRequest $request)
    {
        // 1. Estrai in modo sicuro le credenziali
        $email = $request->input('email');
        $password = $request->input('password');

        if (!$email || !$password) {
            return null;
        }

        // 2. Trova l'utente
        $user = User::where('email', $email)->first();

        if ($user) {
            // 3. Recupera il pepper (con un fallback vuoto se manca nel config)
            $pepper = config('app.pepper', '');
            
            // 4. Ricostruisci la stringa esatta: Password + Pepper + Salt
            $passwordWithPepper = $password . $pepper . ($user->salt ?? '');

            // 5. Verifica l'hash
            if (Hash::check($passwordWithPepper, $user->password)) {
                // Importante: Fortify gestisce autonomamente il login e la sessione.
                // Restituire l'istanza $user è sufficiente per confermare il login.
                return $user;
            }
        }

        // Se fallisce il controllo, ritorna null (Fortify lancerà l'errore delle credenziali)
        return null;
    }
}
*/