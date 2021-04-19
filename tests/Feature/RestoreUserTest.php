<?php

namespace Luilliarcec\UserCommands\Tests\Feature;

use Luilliarcec\UserCommands\Tests\Models\User;
use Luilliarcec\UserCommands\Tests\TestCase;

class RestoreUserTest extends TestCase
{
    /** @test */
    public function check_that_a_error_is_returned_when_the_user_does_not_exist()
    {
        $this->artisan('user:restore luis@email.com')
            ->expectsOutput('Oops, the user was not found!')
            ->assertExitCode(1);
    }

    /** @test */
    public function the_user_is_restored_by_email()
    {
        User::create([
            'name' => 'Luis Arce',
            'email' => 'luis@email.com',
            'password' => bcrypt('12341234'),
        ])->delete();

        $this->artisan('user:restore luis@email.com')
            ->expectsOutput('User restored successfully!')
            ->assertExitCode(0);

        $this->assertDatabaseHas('users', [
            'email' => 'luis@email.com',
            'deleted_at' => null
        ]);
    }

    /** @test */
    public function the_user_is_restored_by_id()
    {
        User::create([
            'name' => 'Luis Arce',
            'email' => 'luis@email.com',
            'password' => bcrypt('12341234'),
        ])->delete();

        $this->artisan('user:restore 1')
            ->expectsOutput('User restored successfully!')
            ->assertExitCode(0);

        $this->assertDatabaseHas('users', [
            'email' => 'luis@email.com',
            'deleted_at' => null
        ]);
    }

    /** @test */
    public function the_user_is_restored_by_field_shortcut_value()
    {
        User::create([
            'name' => 'Luis Arce',
            'username' => 'larcec',
            'email' => 'luis@email.com',
            'password' => bcrypt('12341234'),
        ])->delete();

        $this->artisan('user:restore larcec -f username')
            ->expectsOutput('User restored successfully!')
            ->assertExitCode(0);

        $this->assertDatabaseHas('users', [
            'email' => 'luis@email.com',
            'deleted_at' => null
        ]);
    }

    /** @test */
    public function the_user_is_restored_by_field_value()
    {
        User::create([
            'name' => 'Luis Arce',
            'username' => 'larcec',
            'email' => 'luis@email.com',
            'password' => bcrypt('12341234'),
        ])->delete();

        $this->artisan('user:restore larcec --field username')
            ->expectsOutput('User restored successfully!')
            ->assertExitCode(0);

        $this->assertDatabaseHas('users', [
            'email' => 'luis@email.com',
            'deleted_at' => null
        ]);
    }
}
