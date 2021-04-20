<?php

namespace Luilliarcec\UserCommands\Tests\Commands;

class Kernel extends \Orchestra\Testbench\Console\Kernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        CreateNewUserCommand::class,
    ];
}
