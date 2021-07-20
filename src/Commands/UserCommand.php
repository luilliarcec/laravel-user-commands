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
     * @var array
     */
    protected $fields;

    /**
     * @var array
     */
    protected $rules;

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
        $this->fields = config('user-commands.fields');
        $this->rules = config('user-commands.rules');
        $this->permission = config('user-commands.permission.model');
        $this->role = config('user-commands.role.model');
    }

    /**
     * Create a new user instance
     *
     * @return mixed
     */
    protected function newUserInstance()
    {
        return new $this->user;
    }

    /**
     * Create a new permission instance
     *
     * @return mixed
     */
    protected function newPermissionInstance()
    {
        return new $this->permission;
    }

    /**
     * Create a new role instance
     *
     * @return mixed
     */
    protected function newRoleInstance()
    {
        return new $this->role;
    }

    /**
     * Get user model
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    protected function getUserModel(): ?\Illuminate\Database\Eloquent\Model
    {
        $value = $this->argument('value');
        $field = $this->option('field');

        if ($field) {
            return $this->user::query()
                ->where($field, $value)
                ->first();
        }

        return $this->user::query()
            ->where('email', $value)
            ->first() ?: $this->user::query()->find($value);
    }
}
