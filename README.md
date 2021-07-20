# Laravel User Commands V3

![run-tests](https://github.com/luilliarcec/laravel-user-commands/workflows/run-tests/badge.svg)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/luilliarcec/laravel-user-commands.svg)](https://packagist.org/packages/luilliarcec/laravel-user-commands)
[![Quality Score](https://img.shields.io/scrutinizer/g/luilliarcec/laravel-user-commands)](https://scrutinizer-ci.com/g/luilliarcec/laravel-user-commands)
[![Total Downloads](https://img.shields.io/packagist/dt/luilliarcec/laravel-user-commands)](https://packagist.org/packages/luilliarcec/laravel-user-commands)
[![GitHub license](https://img.shields.io/github/license/luilliarcec/laravel-user-commands)](https://github.com/luilliarcec/laravel-user-commands/blob/develop/LICENSE.md)

If like me, you have ever considered having commands to manipulate users within your application, this package will help
you.

## Installation

You can install the package via composer:

```bash
composer require luilliarcec/laravel-user-commands
```

Now publish the configuration file into your app's config directory, by running the following command:

```bash
php artisan vendor:publish --provider="Luilliarcec\UserCommands\UserCommandsServiceProvider"
```

That is all. 😀

## Usage

The package has 4 basic commands

| Commands | Description | 
| --- | --- | 
| user:create | Create a new user in your app | 
| user:reset-password | Restore a user's password | 
| user:delete | Delete a user | 
| user:restore | Restore a user |

### Create Users

```bash
php artisan user:create
```

###### Fields

All the fields defined in your fillable property of your model will be used when executing the command. If you want to
add more fields you can do it from your config file (your file takes precedence over your model, so if you define the
fields in your config file the fields of your fillable property will be ignored)

###### Rules

Whether you have fields defined in your model or in the configuration file, the `filled` rule will be dynamically
applied to those fields. If you want to add custom rules you can do it from the rules key from your configuration file,
these will be merged in such a way that those fields that have not been given a custom rule will use the `filled` rule
by default.

After all, you are free to extend the command and configure it to your liking.

```bash
php artisan user:create -a username:larcec -a "other_field:Value of field"
```

or

```bash
php artisan user:create --attributes=username:larcec --attributes="other_field:Value of field"
```

If you want to mark the user's email as verified you can pass the argument `--verified` Ex.:

```bash
php artisan user:create --verified
```

If your model uses roles and permissions, you must configure the model of the roles and permissions and the name of the
relationships in the configuration file.

You can then pass the permissions or roles as arguments.

```bash
php artisan user:create -p "user-create" -p "user-edit"
```

It should be noted that the `roles` and `permissions`  
must already exist in your database and will be searched by the `name` field

Once the user has been created, the `notification` that it has been created will be sent if it implements
the `MustVerifyEmail` interface and if the `verification.verify` route name exists

If you want to apply your own logic or just save default data for all users, you can extend the command and apply your
necessary logic, or copy the `prepareForSave` method and add your necessary data. Ex.:

```php
<?php

namespace App\Commands;

use Luilliarcec\UserCommands\Commands\CreateNewUserCommand as CreateNewUserCommandBase;

class CreateNewUserCommand extends CreateNewUserCommandBase
{
    protected function prepareForSave(): array
    {
        return $this->merge([
            'password' => Hash::make($this->data['password']),
            'username' => Username::make($this->data['name']),
        ]);
    }
}
```

`$this->data` will contain the data of the user that was asked, if you want to access your data that you entered with
the `--attribute` flag you can do it by calling the `attributes` function which will return a `key => value`
array with your data.

### Reset Password User

The command to `reset password user` receives the value parameter as required. It will be searched by `email` and if it
is not found it will be searched by `id`

```bash
php artisan user:reset-password luis@email.com
```

However if you want to search for a specific field you can pass it after the value

```bash
php artisan user:reset-password larcec -f username
```

or

```bash
php artisan user:reset-password larcec --field username
```

After executing the command it will ask you to enter a new password and confirm it

### Delete Users

In the same way as the command to `reset password user`, the user is searched by email or id or by specifying a specific
field.

```bash
php artisan user:delete luis@email.com
```

or

```bash
php artisan user:delete larcec --field username
```

If your model uses logical elimination, this is executed by default, however if you want to eliminate completely you can
pass the --force argument

```bash
php artisan user:delete larcec --field username --force
```

### Restore Users

In the same way as the command to `reset password user`, the user is searched by email or id or by specifying a specific
field.

```bash
php artisan user:restore luis@email.com
```

or

```bash
php artisan user:restore larcec --field username
```

Note that this command will only run if your model uses SoftDelete trait

## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email luilliarcec@gmail.com instead of using the issue tracker.

## Credits

- [Luis Andrés Arce C.](https://github.com/luilliarcec)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
