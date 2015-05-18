<?php namespace App\Core\Commands;

use Slack;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class DeployedNotificationCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'varaa:deployed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a message to Slack notifying about new deployments';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $instance = $this->argument('instance');
        $branch = $this->argument('branch');

        Slack::send("Server `$instance` has been :shipit: at branch `$branch`.");

    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('instance', InputArgument::REQUIRED, 'Name of the deployed instance'),
            array('branch', InputArgument::REQUIRED, 'The branch deployed'),
        );
    }
}
