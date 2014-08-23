<?php namespace App\Core\Commands;

use App\Core\Models\User;
use App\Core\Models\Role;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class MakeAdminCommand extends Command
{
    /**
	 * The console command name.
	 *
	 * @var string
	 */
    protected $name = 'varaa:admin';

    /**
	 * The console command description.
	 *
	 * @var string
	 */
    protected $description = 'Add user with the given email to role Admin';

    /**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
    public function fire()
    {
        $email = $this->argument('email');
        // Find user
        $user = User::where('email', '=', $email)->first();
        if (!$user) {
            $this->error('Cannot find user with this email.');
            exit;
        }

        // Find the group
        $role = Role::where('name', 'Admin')->first();
        if (!$role) {
            $this->error('We do not have Admin role in database');
            exit;
        }

        if ($user->hasRole($role->name)) {
            $this->error('I am sorry Captain but Ronan is attacking us.');
            exit;
        }

        $result = $user->attachRole($role);
        $this->info('Welcome '.$email.' as a new member of Nova Corps');
    }

    /**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
    protected function getArguments()
    {
        return array(
            array('email', InputArgument::REQUIRED, 'Email of user'),
        );
    }
}
