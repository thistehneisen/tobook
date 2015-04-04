<?php namespace App\Core\Commands;

use App;
use Config;
use Illuminate\Console\Command;
use Indatus\Dispatcher\Drivers\Cron\Scheduler;
use Indatus\Dispatcher\Scheduling\Schedulable;
use Indatus\Dispatcher\Scheduling\ScheduledCommand;
use Log;

class BackupDatabaseCommand extends ScheduledCommand
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
     * When a command should run
     *
     * @param  Scheduler                                  $scheduler
     * @return \Indatus\Dispatcher\Scheduling\Schedulable
     */
    public function schedule(Schedulable $scheduler)
    {
        // Run this backup command every 15 minutes
        return $scheduler->everyMinutes(15);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        // No need to run backup in some environments
        $env = [
            'stag'      => true,
            'local'     => true,
            'testing'   => true,
            'accepting' => true,
        ];
        if (isset($env[App::environment()])) {
            return;
        }

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
        if (file_exists($backup_file)) {
            unlink($backup_file);
        }
        Log::info('Backup was created at '.date('Y-m-d H:i:s'));
    }
}
