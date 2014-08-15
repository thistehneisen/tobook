<?php namespace App\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class GenerateConfigsCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'varaa:generate-configs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate local configurations';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $appConfig = <<< CONF
<?php

return [
    'debug' => true,
    'locale' => 'fi',
];
CONF;
        $mailConfig = <<< CONF
<?php

return [
    'pretend' => true
];
CONF;
        $dbConfig = <<< CONF
<?php

return [
    'fetch' => PDO::FETCH_CLASS,
    'default' => 'mysql',
    'connections' => [
        'mysql' => [
            'driver'    => 'mysql',
            'host'      => 'localhost',
            'database'  => 'varaa',
            'username'  => 'root',
            'password'  => '',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => 'varaa_',
        ],
    ],
];
CONF;
        if (!file_exists(app_path().'/config/local/app.php')) {
            $this->comment('Generate local app.php');
            file_put_contents(app_path().'/config/local/app.php', $appConfig);    
        }
        if (!file_exists(app_path().'/config/local/mail.php')) {
            $this->comment('Generate local mail.php');
            file_put_contents(app_path().'/config/local/mail.php', $mailConfig);
        }
        if (!file_exists(app_path().'/config/local/database.php')) {
            $this->comment('Generate local database.php');
            file_put_contents(app_path().'/config/local/database.php', $dbConfig);
        }
        $this->info('DONE');
    }
}
