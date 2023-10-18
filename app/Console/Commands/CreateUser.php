<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class CreateUser extends Command
{
    protected $signature = 'app:user-create';
    protected $description = 'Create user account';

    public function handle()
    {
        $name = $this->askValid('Write a name', 'required|string');
        $email = $this->askValid('Write an email', 'required|email|unique:users,email');
        $password = $this->askValid('Write a password', [
            'required',
            Password::min(8)->mixedCase()->letters()->numbers()->symbols(),
        ]);

        User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $this->info('User "' . $name . '" with email "' . $email . '" created.');
        return 0;
    }

    private function askValid($question, $rules)
    {
        $value = $this->ask($question);
        $validator = validator(['value' => $value], ['value' => $rules]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return $this->askValid($question, $rules);
        }

        return $value;
    }
}

