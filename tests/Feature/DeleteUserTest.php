<?php

namespace Luilliarcec\UserCommands\Tests\Feature;

use Luilliarcec\UserCommands\Tests\Models\User;
use Luilliarcec\UserCommands\Tests\TestCase;

class DeleteUserTest extends TestCase
{
    /** @test */
    public function check_that_a_error_is_returned_when_the_user_does_not_exist()
    {
        $this->artisan('user:delete luis@email.com')
            ->expectsOutput('Oops, the user was not found!')
            ->assertExitCode(1);
    }

    /** @test */
    public function the_user_is_deleted_by_email()
    {
        User::create([
            'name' => 'Luis Arce',
            'email' => 'luis@email.com',
            'password' => bcrypt('12341234')
        ]);

        $this->artisan('user:delete luis@email.com')
            ->expectsOutput('User deleted successfully!')
            ->assertExitCode(0);

        $this->assertDatabaseMissing('users', [
            'email' => 'luis@email.com',
            'deleted_at' => null
        ]);
    }

    /** @test */
    public function the_user_is_deleted_by_id()
    {
        User::create([
            'name' => 'Luis Arce',
            'email' => 'luis@email.com',
            'password' => bcrypt('12341234')
        ]);

        $this->artisan('user:delete 1')
            ->expectsOutput('User deleted successfully!')
            ->assertExitCode(0);

        $this->assertDatabaseMissing('users', [
            'email' => 'luis@email.com',
            'deleted_at' => null
        ]);
    }

    /** @test */
    public function the_user_is_deleted_by_field_value()
    {
        User::create([
            'name' => 'Luis Arce',
            'username' => 'larcec',
            'email' => 'luis@email.com',
            'password' => bcrypt('12341234')
        ]);

        $this->artisan('user:delete larcec username')
            ->expectsOutput('User deleted successfully!')
            ->assertExitCode(0);

        $this->assertDatabaseMissing('users', [
            'email' => 'luis@email.com',
            'deleted_at' => null
        ]);
    }

    /** @test */
    public function the_user_is_force_deleted()
    {
        User::create([
            'name' => 'Luis Arce',
            'username' => 'larcec',
            'email' => 'luis@email.com',
            'password' => bcrypt('12341234')
        ]);

        $this->artisan('user:delete luis@email.com --force')
            ->expectsOutput('User deleted successfully!')
            ->assertExitCode(0);

        $this->assertDatabaseMissing('users', [
            'email' => 'luis@email.com',
        ]);
    }
}
