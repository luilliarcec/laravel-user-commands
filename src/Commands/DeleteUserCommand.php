<?php

namespace Luilliarcec\UserCommands\Commands;


class DeleteUserCommand extends UserCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:delete
                            {value : Get user for a value (by email, id)}
                            {field? : Field to search by}
                            {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete a user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = $this->getUserModel();

        if (is_null($user)) {
            $this->error('Oops, the user was not found!');
            return 1;
        }

        if ($this->option('force')) {
            $user->forceDelete();
        } else {
            $user->delete();
        }

        $this->info('User deleted successfully!');

        return 0;
    }

    /**
     * Get user model
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    protected function getUserModel()
    {
        $value = $this->argument('value');
        $field = $this->argument('field');

        if ($field) {
            return $this->user::query()
                ->where($field, $value)
                ->first();
        }

        return $this->user::query()
            ->where('email', $value)
            ->first() ?: $this->user::query()->find($value);
    }
}
