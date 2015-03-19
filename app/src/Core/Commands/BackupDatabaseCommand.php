<?php namespace App\Core\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Config;
class BackupDatabaseCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'varaa:backup_db';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup database and send it to backup server';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $db_name = Config::get('database.connections.mysql.database');
        $db_user = escapeshellarg(Config::get('database.connections.mysql.username'));
        $db_pwd  = escapeshellarg(Config::get('database.connections.mysql.password'));
        $backup_name =  escapeshellarg(sprintf('%s-%s.sql', $db_name, date('Ymd.His')));
        $command = sprintf("/usr/bin/mysqldump -u%s -p%s %s > %s", $db_user, $db_pwd, $db_name, $backup_name);
        exec($command);


    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array();
    }
}
