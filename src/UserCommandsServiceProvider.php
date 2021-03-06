<?php

namespace Luilliarcec\UserCommands;

use Illuminate\Support\ServiceProvider;
use Luilliarcec\UserCommands\Commands\CreateNewUserCommand;
use Luilliarcec\UserCommands\Commands\DeleteUserCommand;
use Luilliarcec\UserCommands\Commands\ResetUserPasswordCommand;
use Luilliarcec\UserCommands\Commands\RestoreUserCommand;

class UserCommandsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('user-commands.php'),
            ], 'config');

            $this->commands([
                CreateNewUserCommand::class,
                ResetUserPasswordCommand::class,
                DeleteUserCommand::class,
                RestoreUserCommand::class,
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'user-commands');
    }
}
