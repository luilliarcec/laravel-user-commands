<?php

namespace Luilliarcec\UserCommands\Commands;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ResetUserPasswordCommand extends UserCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:reset-password
                            {value : Get user for a value (by email, id)}
                            {field? : Field to search by}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restore a user`s password';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $password = $this->secret('password');
        $password_confirmation = $this->secret('password confirmation');

        $passes = $this->validate([
            'password' => $password,
            'password_confirmation' => $password_confirmation
        ]);

        if (!$passes) return 1;

        $user = $this->getUserModel();

        if (is_null($user)) {
            $this->error('Oops, the user was not found!');
            return 1;
        }

        $user->forceFill([
            'password' => Hash::make($password)
        ])->save();

        $this->info('User password was successfully restored!');

        return 0;
    }

    /**
     * Validate data and show errors
     *
     * @param array $data
     * @return bool
     */
    protected function validate(array $data): bool
    {
        $validator = Validator::make($data, $this->rules());

        if ($validator->fails()) {
            $this->info('User not created. See error messages below:');

            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return false;
        }

        return true;
    }

    /**
     * Validation rules
     *
     * @return array
     */
    protected function rules(): array
    {
        return [
            'password' => ['required', 'string', 'confirmed'],
        ];
    }
}
