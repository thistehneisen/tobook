<?php namespace App\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CreateDummyUsersCommand extends Command
{
    /**
	 * The console command name.
	 *
	 * @var string
	 */
    protected $name = 'varaa:dummy-users';

    /**
	 * The console command description.
	 *
	 * @var string
	 */
    protected $description = 'Create users with username `tester_X` and password `nopassword`';

    /**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
    public function fire()
    {
        $this->info('Creating...');

        $username = 'tester_';
        if ($this->argument('username')) {
            $username = $this->argument('username');
        }

        $password = 'nopassword';
        if ($this->argument('password')) {
            $password = $this->argument('password');
        }

        $number = 100;
        if ($this->option('number')) {
            $number = (int) $this->option('number');
        }

        $i = 1;
        while ($i++ < $number) {
            $user = new \User();
            $user->confirmed             = 1;
            $user->username              = $username.$i;
            $user->email                 = $username.$i.'@varaa.com';
            $user->password              = $password;
            $user->password_confirmation = $password;
            $user->save();
        }

        $this->info("Now you have $number users with `$username` and password `$password`. Enjoy!");
    }

    /**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
    protected function getArguments()
    {
        return array(
            array('username', InputArgument::OPTIONAL, 'Username prefix'),
            array('password', InputArgument::OPTIONAL, 'The desired password'),
        );
    }

    /**
	 * Get the console command options.
	 *
	 * @return array
	 */
    protected function getOptions()
    {
        return array(
            array('number', null, InputOption::VALUE_OPTIONAL, 'How many users do you want to create?', null),
        );
    }

}
