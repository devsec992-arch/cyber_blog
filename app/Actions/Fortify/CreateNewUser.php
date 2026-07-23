<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
  /*  public function create(array $input): User
    {
        $pepper = config('app.pepper');
        $salt=Str::random(16);
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
        ])->validate();

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password'].$salt.$pepper),
            'salt' => $salt,
        ]);
    }*/ public function create(array $input): User
    {
        // 1. Prima si esegue la validazione per evitare calcoli inutili se i dati sono errati
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
        ])->validate();

        // 2. Genera il salt e recupera il pepper
        $salt = Str::random(16);
        $pepper = config('app.pepper', '');

        // 3. Costruisci la stringa nell'ORDINE ESATTO del login: Password -> Pepper -> Salt
        $passwordWithPepper = $input['password'] . $pepper . $salt;

        // 4. Crea l'utente nel database
        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($passwordWithPepper),
            'salt' => $salt,
        ]);
    }







}
