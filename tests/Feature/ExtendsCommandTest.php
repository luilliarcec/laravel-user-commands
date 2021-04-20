<?php

namespace Luilliarcec\UserCommands\Tests\Feature;

use Luilliarcec\UserCommands\Tests\Commands\Kernel;
use Luilliarcec\UserCommands\Tests\Models\User;
use Luilliarcec\UserCommands\Tests\TestCase;

class ExtendsCommandTest extends TestCase
{
    /**
     * Resolve application Console Kernel implementation.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return void
     */
    protected function resolveApplicationConsoleKernel($app)
    {
        $app->singleton('Illuminate\Contracts\Console\Kernel', Kernel::class);
    }

    /** @test */
    public function check_that_the_create_user_commands_are_override()
    {
        $this->artisan('user:create')
            ->expectsQuestion('name', 'Luis Arce')
            ->expectsQuestion('email', 'luis@email.com')
            ->expectsQuestion('password', 'password')
            ->expectsQuestion('password confirmation', 'password')
            ->expectsOutput('The user was created successfully!')
            ->assertExitCode(0);

        $this->assertEquals('username_random', User::query()->first()->username);
    }
}
