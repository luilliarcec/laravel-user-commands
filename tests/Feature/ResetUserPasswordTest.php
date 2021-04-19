<?php

namespace Luilliarcec\UserCommands\Tests\Feature;

use Luilliarcec\UserCommands\Tests\Models\User;
use Luilliarcec\UserCommands\Tests\TestCase;

class ResetUserPasswordTest extends TestCase
{
    /** @test */
    public function check_that_the_password_is_confirmed()
    {
        $this->artisan('user:reset-password luis@email.com')
            ->expectsQuestion('password', '12341234')
            ->expectsQuestion('password confirmation', 'password')
            ->expectsOutput('The password confirmation does not match.')
            ->assertExitCode(1);
    }

    /** @test */
    public function check_that_a_error_is_returned_when_the_user_does_not_exist()
    {
        $this->artisan('user:reset-password luis@email.com')
            ->expectsQuestion('password', 'password')
            ->expectsQuestion('password confirmation', 'password')
            ->expectsOutput('Oops, the user was not found!')
            ->assertExitCode(1);
    }

    /** @test */
    public function the_user_is_reseted_by_email()
    {
        User::create([
            'name' => 'Luis Arce',
            'email' => 'luis@email.com',
            'password' => bcrypt('12341234')
        ]);

        $this->artisan('user:reset-password luis@email.com')
            ->expectsQuestion('password', 'password')
            ->expectsQuestion('password confirmation', 'password')
            ->expectsOutput('User password was successfully restored!')
            ->assertExitCode(0);

        $this->assertCredentials([
            'email' => 'luis@email.com',
            'password' => 'password'
        ]);
    }

    /** @test */
    public function the_user_is_reseted_by_id()
    {
        User::create([
            'name' => 'Luis Arce',
            'email' => 'luis@email.com',
            'password' => bcrypt('12341234')
        ]);

        $this->artisan('user:reset-password 1')
            ->expectsQuestion('password', 'password')
            ->expectsQuestion('password confirmation', 'password')
            ->expectsOutput('User password was successfully restored!')
            ->assertExitCode(0);

        $this->assertCredentials([
            'email' => 'luis@email.com',
            'password' => 'password'
        ]);
    }

    /** @test */
    public function the_user_is_reseted_by_field_shortcut_value()
    {
        User::create([
            'name' => 'Luis Arce',
            'username' => 'larcec',
            'email' => 'luis@email.com',
            'password' => bcrypt('12341234')
        ]);

        $this->artisan('user:reset-password larcec -f username')
            ->expectsQuestion('password', 'password')
            ->expectsQuestion('password confirmation', 'password')
            ->expectsOutput('User password was successfully restored!')
            ->assertExitCode(0);

        $this->assertCredentials([
            'email' => 'luis@email.com',
            'password' => 'password'
        ]);
    }

    /** @test */
    public function the_user_is_reseted_by_field_value()
    {
        User::create([
            'name' => 'Luis Arce',
            'username' => 'larcec',
            'email' => 'luis@email.com',
            'password' => bcrypt('12341234')
        ]);

        $this->artisan('user:reset-password larcec --field username')
            ->expectsQuestion('password', 'password')
            ->expectsQuestion('password confirmation', 'password')
            ->expectsOutput('User password was successfully restored!')
            ->assertExitCode(0);

        $this->assertCredentials([
            'email' => 'luis@email.com',
            'password' => 'password'
        ]);
    }
}
