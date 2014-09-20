<?php namespace App\Core\Commands;

use App\Core\Models\Role;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CreateRoleConsumerCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'varaa:create-role-consumer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create role Consumer';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        // Check if there is an existing role with the same name
        $role = Role::where('name', 'Consumer')->first();
        if ($role === null) {
            $role = new Role();
            $role->name = 'Consumer';
            $role->save();
        }

        $this->info('Created');
    }

}
