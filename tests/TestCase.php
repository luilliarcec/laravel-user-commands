<?php

namespace Luilliarcec\UserCommands\Tests;

use Luilliarcec\UserCommands\Tests\Models\User;
use Luilliarcec\UserCommands\UserCommandsServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

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
        return [UserCommandsServiceProvider::class];
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
        $app['config']->set('user-commands.permission.model', null);
        $app['config']->set('user-commands.role.model', null);
    }
}
