<?php namespace App\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class InstallCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'varaa:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run required commands to install new Varaa';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $this->comment('Generate local configurations');
        $this->call('varaa:generate-configs');
        $this->comment('Running migrations');
        $this->call('migrate');
        $this->comment('Moving users from old table to new schema');
        $this->call('varaa:move-users');
        $this->comment('Fixing references of old schema');
        $this->call('varaa:fix-schema');
        $this->info('DONE');
    }
}
