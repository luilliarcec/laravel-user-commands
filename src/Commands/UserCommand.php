<?php

namespace Luilliarcec\UserCommands\Commands;

use Illuminate\Console\Command;

class UserCommand extends Command
{
    /**
     * @var \Illuminate\Foundation\Auth\User|string|null
     */
    protected $user;

    /**
     * @var \Illuminate\Database\Eloquent\Model|string|null
     */
    protected $permission;

    /**
     * @var \Illuminate\Database\Eloquent\Model|string|null
     */
    protected $role;

    /**
     * UserCommand constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->user = config('user-commands.user');
        $this->permission = config('user-commands.permission.model');
        $this->role = config('user-commands.role.model');
    }

    protected function newUserInstance()
    {
        return new $this->user;
    }

    protected function newPermissionInstance()
    {
        return new $this->permission;
    }

    protected function newRoleInstance()
    {
        return new $this->role;
    }
}
