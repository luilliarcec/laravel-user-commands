<?php

namespace Luilliarcec\UserCommands\Tests\Commands;

use Illuminate\Support\Facades\Hash;
use Luilliarcec\UserCommands\Commands\CreateNewUserCommand as CreateNewUserCommandBase;

class CreateNewUserCommand extends CreateNewUserCommandBase
{
    protected function prepareForSave(): array
    {
        $this->data['password'] = Hash::make($this->data['password']);

        return $this->merge([
            'username' => 'username_random'
        ]);
    }
}
