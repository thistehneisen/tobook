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
        $backup_name = sprintf('%s-%s.sql.gz', $db_name, date('Ymd.His'));
        //Dump and compress data to a gzip file
        $backup_command = sprintf("/usr/bin/mysqldump -u%s -p%s %s | gzip > %s", $db_user, $db_pwd, $db_name, $backup_name);
        exec($backup_command);
        //Copy backup file to backup server
        $copy_command = sprintf('scp %s root@46.101.51.135:/srv/backup', $backup_name);
        exec($copy_command);
        //Delete backup file on local server
        $backup_file = realpath('./' . $backup_name);
        if(file_exists($backup_file)) {
            unlink($backup_file);
        }
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
