<?php

namespace Luilliarcec\UserCommands\Tests;

use Luilliarcec\UserCommands\UserCommandsServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
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
}
