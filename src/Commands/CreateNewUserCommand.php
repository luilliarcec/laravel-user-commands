<?php

namespace Luilliarcec\UserCommands\Commands;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CreateNewUserCommand extends UserCommand
{
    /**
     * Passwords fields
     */
    protected const PASS_FIELDS = ['password', 'pass'];

    /**
     * Valid data of the user to be created
     *
     * @var array
     */
    protected $data;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create
                            {--verified : Mark the user`s email as verified}
                            {--a|attributes=* : Add additional attributes to the user}
                            {--p|permissions=* : Assign permissions to the user.}
                            {--r|roles=* : Assign roles to the user.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user in your app';

    /**
     * Execute the console command.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function handle(): int
    {
        $this->user = $this->newUserInstance();

        $passes = $this->validate($this->askDefaultField());

        if (!$passes) return 1;

        DB::transaction(function () {
            $this->save($this->prepareForSave());

            $this->verified();
            $this->permissions();
            $this->roles();

            $this->info('The user was created successfully!');
        });

        return 0;
    }

    /**
     * Ask default data
     *
     * @return array
     */
    protected function askDefaultField(): array
    {
        return [
            'name' => $this->ask('name'),
            'email' => $this->ask('email'),
            'password' => $this->ask('password'),
            'password_confirmation' => $this->ask('password confirmation'),
        ];
    }

    /**
     * Prepare the data to save and include your necessary data
     *
     * @return array
     */
    protected function prepareForSave(): array
    {
        $this->data['password'] = Hash::make($this->data['password']);

        return $this->merge([]);
    }

    /**
     * Merge new input into the current data user.
     *
     * @param array $data
     * @return array
     */
    protected function merge(array $data): array
    {
        return array_merge(
            $this->attributes(),
            $this->data,
            $data
        );
    }

    /**
     * Validate data and show errors
     *
     * @param array $data
     * @return bool
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validate(array $data): bool
    {
        $validator = Validator::make($data, $this->rules());

        if ($validator->fails()) {
            $this->info('User not created. See error messages below:');

            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return false;
        }

        $this->data = $validator->validated();

        return true;
    }

    /**
     * Validation rules
     *
     * @return array
     */
    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique($this->user->getTable())],
            'password' => ['required', 'string', 'confirmed'],
        ];
    }

    /**
     * The record in the database persists
     *
     * @param array $data
     */
    protected function save(array $data)
    {
        $this->user->fill($data)->save();
    }

    /**
     * Mark the email as verified or send a verification email
     */
    protected function verified(): ?bool
    {
        if ($this->option('verified')) {
            return $this->user->markEmailAsVerified();
        }

        if (Route::has('verification.verify') && $this->user instanceof MustVerifyEmail) {
            $this->user->sendEmailVerificationNotification();
        }

        return null;
    }

    /**
     * Assign permissions to the user.
     */
    protected function permissions()
    {
        if (is_null($this->permission)) return;

        $relation = config('user-commands.permission.relation');

        $options = $this->option('permissions');

        $permissions = $this->permission::query()
            ->whereIn('name', $options)
            ->get('id')
            ->modelKeys();

        $this->user->$relation()->attach($permissions);
    }

    /**
     * Assign roles to the user.
     */
    protected function roles()
    {
        if (is_null($this->role)) return;

        $relation = config('user-commands.role.relation');

        $options = $this->option('roles');

        $permissions = $this->role::query()
            ->whereIn('name', $options)
            ->get('id')
            ->modelKeys();

        $this->user->$relation()->attach($permissions);
    }

    /**
     * Gets the additional attributes
     *
     * @return array
     */
    protected function attributes(): array
    {
        $attributes = [];

        $option = $this->option('attributes');

        foreach ($option as $attribute) {
            [$key, $value] = explode(':', $attribute);

            $attributes[$key] = $value;
        }

        return $attributes;
    }
}
