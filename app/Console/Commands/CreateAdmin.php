<?php

namespace App\Console\Commands;

use App\Auth\AdminAccounts;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

use function Laravel\Prompts\password;
use function Laravel\Prompts\text;

class CreateAdmin extends Command
{
    protected $signature = 'admin:create
        {--name= : Display name}
        {--email= : Login email}
        {--password= : Password (prompted securely when omitted)}';

    protected $description = 'Create (or reset the password of) an admin account for the /admin lead desk';

    public function handle(AdminAccounts $accounts): int
    {
        $name = $this->option('name') ?: text('Name', default: 'Administrator', required: true);
        $email = $this->option('email') ?: text('Email', required: true);
        $plain = $this->option('password') ?: password('Password (min 10 characters, letters, numbers & symbols)', required: true);

        $validator = Validator::make(
            ['name' => $name, 'email' => $email, 'password' => $plain],
            [
                'name' => ['required', 'string', 'max:120'],
                'email' => ['required', 'email', 'max:190'],
                'password' => ['required', Password::min(10)->letters()->numbers()->symbols()],
            ]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        [$account, $created] = $accounts->save([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($plain),
            'remember_token' => null, // signs out "remember me" logins after a password reset
        ]);

        $this->info(($created ? 'Created' : 'Updated')." admin account {$account['email']}.");

        return self::SUCCESS;
    }
}
