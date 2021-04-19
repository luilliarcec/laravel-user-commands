<?php

namespace Luilliarcec\UserCommands\Commands;


class RestoreUserCommand extends UserCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:restore
                            {value : Get user for a value (by email, id)}
                            {--f|field= : Field to search by}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restore a user';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $user = $this->getUserModel();

        if (is_null($user)) {
            $this->error('Oops, the user was not found!');
            return 1;
        }

        if (!method_exists($user, 'restore')) {
            $this->error('Oops, user does not use softdelete!');
            return 1;
        }

        $user->restore();

        $this->info('User restored successfully!');

        return 0;
    }

    /**
     * Get user model
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    protected function getUserModel(): ?\Illuminate\Database\Eloquent\Model
    {
        $value = $this->argument('value');
        $field = $this->option('field');

        if ($field) {
            return $this->user::onlyTrashed()
                ->where($field, $value)
                ->first();
        }

        return $this->user::onlyTrashed()
            ->where('email', $value)
            ->first() ?: $this->user::onlyTrashed()->find($value);
    }
}
