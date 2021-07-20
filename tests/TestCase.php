<?php

namespace Luilliarcec\UserCommands\Tests;

use Luilliarcec\UserCommands\Tests\Models\User;
use Luilliarcec\UserCommands\UserCommandsServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionServiceProvider;

class TestCase extends Orchestra
{
    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
    }

    /**
     * Get package providers.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            UserCommandsServiceProvider::class,
            PermissionServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        /** Config */
        $app['config']->set('user-commands.user', User::class);

        $app['config']->set('user-commands.fields', [
            'name',
            'email',
            'password'
        ]);

        $app['config']->set('user-commands.hash_fields', ['password']);

        $app['config']->set('user-commands.rules', [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:254|unique:users',
            'password' => 'required|string|confirmed',
        ]);

        $app['config']->set('user-commands.permission.model', Permission::class);
        $app['config']->set('user-commands.role.model', Role::class);

        $app['config']->set('auth.providers', [
            'users' => [
                'driver' => 'eloquent',
                'model' => User::class,
            ]
        ]);
    }
}
