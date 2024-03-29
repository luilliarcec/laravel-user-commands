<?php

namespace Luilliarcec\UserCommands\Tests\Feature;

use Luilliarcec\UserCommands\Tests\Models\UserFillable as User;
use Luilliarcec\UserCommands\Tests\TestCase;
use Spatie\Permission\Models\Permission;

class CreateNewUserWithFillableDataTest extends TestCase
{
    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->app['config']->set('user-commands.user', User::class);
        $this->app['config']->set('user-commands.fields', []);
        $this->app['config']->set('user-commands.rules', []);
    }

    /** @test */
    public function check_that_the_necessary_data_is_validated()
    {
        $this->artisan('user:create')
            ->expectsQuestion('name', null)
            ->expectsQuestion('email', null)
            ->expectsQuestion('password', null)
            ->expectsQuestion('password confirmation', 'password')
            ->expectsQuestion('username', 'larcec')
            ->expectsQuestion('address', 'New York')
            ->expectsOutput('The name field must have a value.')
            ->expectsOutput('The email field must have a value.')
            ->expectsOutput('The password field must have a value.')
            ->assertExitCode(1);

        $this->assertEquals(0, User::query()->count());
    }

    /** @test */
    public function check_that_the_email_is_unique()
    {
        $this->app['config']->set('user-commands.rules', [
            'email' => 'required|string|email|max:254|unique:users'
        ]);

        User::create([
            'name' => 'Luis Arce',
            'email' => 'luis@email.com',
            'password' => '12341234'
        ]);

        $this->artisan('user:create')
            ->expectsQuestion('name', 'Barry Allen')
            ->expectsQuestion('email', 'luis@email.com')
            ->expectsQuestion('password', 'password')
            ->expectsQuestion('password confirmation', 'password')
            ->expectsQuestion('username', 'larcec')
            ->expectsQuestion('address', 'New York')
            ->expectsOutput('The email has already been taken.')
            ->assertExitCode(1);

        $this->assertDatabaseMissing('users', [
            'name' => 'Barry Allen',
        ]);
    }

    /** @test */
    public function check_that_the_password_is_confirmed()
    {
        $this->app['config']->set('user-commands.rules', [
            'password' => 'required|string|confirmed'
        ]);

        $this->artisan('user:create')
            ->expectsQuestion('name', 'Luis Arce')
            ->expectsQuestion('email', 'luis@email.com')
            ->expectsQuestion('password', '12341234')
            ->expectsQuestion('password confirmation', 'password')
            ->expectsQuestion('username', 'larcec')
            ->expectsQuestion('address', 'New York')
            ->expectsOutput('The password confirmation does not match.')
            ->assertExitCode(1);

        $this->assertDatabaseMissing('users', [
            'name' => 'Luis Arce',
        ]);
    }

    /** @test */
    public function check_that_the_user_is_saved()
    {
        $this->artisan('user:create')
            ->expectsQuestion('name', 'Luis Arce')
            ->expectsQuestion('email', 'luis@email.com')
            ->expectsQuestion('password', 'password')
            ->expectsQuestion('password confirmation', 'password')
            ->expectsQuestion('username', 'larcec')
            ->expectsQuestion('address', 'New York')
            ->expectsOutput('The user was created successfully!')
            ->assertExitCode(0);

        $this->assertDatabaseHas('users', [
            'name' => 'Luis Arce',
            'email' => 'luis@email.com',
            'username' => 'larcec',
            'address' => 'New York',
        ]);
    }

    /** @test */
    public function check_that_the_user_is_saved_with_email_verified_at()
    {
        $this->artisan('user:create --verified')
            ->expectsQuestion('name', 'Luis')
            ->expectsQuestion('email', 'luis@email.com')
            ->expectsQuestion('password', 'password')
            ->expectsQuestion('password confirmation', 'password')
            ->expectsQuestion('username', 'larcec')
            ->expectsQuestion('address', 'New York')
            ->expectsOutput('The user was created successfully!')
            ->assertExitCode(0);

        $this->assertDatabaseHas('users', [
            'name' => 'Luis',
            'email' => 'luis@email.com',
            'username' => 'larcec',
            'address' => 'New York',
        ]);

        $this->assertDatabaseMissing('users', [
            'name' => 'Luis',
            'email' => 'luis@email.com',
            'email_verified_at' => null
        ]);
    }

    /** @test */
    public function check_that_the_user_is_saved_with_additional_attributes()
    {
        $this->artisan('user:create --attributes=username:larcec --attributes="address:New York"')
            ->expectsQuestion('name', 'Luis Arce')
            ->expectsQuestion('email', 'luis@email.com')
            ->expectsQuestion('password', 'password')
            ->expectsQuestion('password confirmation', 'password')
            ->expectsQuestion('username', 'dummy')
            ->expectsQuestion('address', 'dummy')
            ->expectsOutput('The user was created successfully!')
            ->assertExitCode(0);

        $this->assertDatabaseHas('users', [
            'name' => 'Luis Arce',
            'email' => 'luis@email.com',
            'username' => 'larcec',
            'address' => 'New York'
        ]);
    }

    /** @test */
    public function check_that_the_user_is_saved_with_additional_short_attributes()
    {
        $this->artisan('user:create -a username:larcec -a "address:New York"')
            ->expectsQuestion('name', 'Luis Arce')
            ->expectsQuestion('email', 'luis@email.com')
            ->expectsQuestion('password', 'password')
            ->expectsQuestion('password confirmation', 'password')
            ->expectsQuestion('username', 'dummy')
            ->expectsQuestion('address', 'dummy')
            ->expectsOutput('The user was created successfully!')
            ->assertExitCode(0);

        $this->assertDatabaseHas('users', [
            'name' => 'Luis Arce',
            'email' => 'luis@email.com',
            'username' => 'larcec',
            'address' => 'New York'
        ]);
    }

    /** @test */
    public function check_that_the_user_is_saved_with_permissions()
    {
        $permission1 = Permission::create(['name' => 'user-create']);
        $permission2 = Permission::create(['name' => 'user-edit']);

        $this->artisan('user:create -p "user-create" -p "user-edit"')
            ->expectsQuestion('name', 'Luis Arce')
            ->expectsQuestion('email', 'luis@email.com')
            ->expectsQuestion('password', 'password')
            ->expectsQuestion('password confirmation', 'password')
            ->expectsQuestion('username', 'dummy')
            ->expectsQuestion('address', 'dummy')
            ->expectsOutput('The user was created successfully!')
            ->assertExitCode(0);

        $this->assertDatabaseHas('users', [
            'name' => 'Luis Arce',
            'email' => 'luis@email.com',
        ]);

        $this->assertDatabaseHas('model_has_permissions', [
            'model_type' => User::class,
            'model_id' => 1,
            'permission_id' => $permission1->getKey()
        ]);

        $this->assertDatabaseHas('model_has_permissions', [
            'model_type' => User::class,
            'model_id' => 1,
            'permission_id' => $permission2->getKey()
        ]);
    }

    /** @test */
    public function check_that_the_user_is_saved_with_password_encrypted()
    {
        $this->artisan('user:create')
            ->expectsQuestion('name', 'Luis Arce')
            ->expectsQuestion('email', 'luis@email.com')
            ->expectsQuestion('password', 'password')
            ->expectsQuestion('password confirmation', 'password')
            ->expectsQuestion('username', 'dummy')
            ->expectsQuestion('address', 'dummy')
            ->expectsOutput('The user was created successfully!')
            ->assertExitCode(0);

        $this->assertCredentials([
            'email' => 'luis@email.com',
            'password' => 'password',
        ]);
    }
}
