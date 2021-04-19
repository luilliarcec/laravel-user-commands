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
    public function handle(): int
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
}
