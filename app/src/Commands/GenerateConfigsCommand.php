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

    protected function generateApppointmentSchedulerConfig()
    {
        //define("ROOT_PATH", realpath(__DIR__.'/../appointment/'));
        require public_path().'/appointment/core/framework/components/pjToolkit.component.php';
        require public_path().'/appointment/app/config/options.inc.php';
        //$config = require realpath(__DIR__.'/../config.php');

        $domain = $input->getArgument('domain');

        $installFolder = '/appointment/';
        $installPath =  public_path().'/appointment/';
        $installUrl = 'http://' . $domain . $installFolder;
        $licenseKey = 'a6bf7b1eb56f9b7a0a0a9ed4acaca1a4';
        $sample = $installPath . '/app/config/config.sample.php';
        $filename = $installPath . '/app/config/config.inc.php';
        $folder = $installPath . '/app/config/';

        if (!file_exists($filename)) {
            $string = file_get_contents($sample);
            $string = str_replace('[hostname]', $config['db']['host'], $string);
            $string = str_replace('[username]', $config['db']['user'], $string);
            $string = str_replace('[password]', $config['db']['password'], $string);
            $string = str_replace('[database]', $config['db']['name'], $string);

            $string = str_replace('[install_folder]', $installFolder, $string);
            $string = str_replace('[install_path]', $installPath, $string);
            $string = str_replace('[install_url]', $installUrl, $string);
            $string = str_replace('[salt]', \pjToolkit::getRandomPassword(8), $string);
            
            $response = file_get_contents(base64_decode("aHR0cDovL3N1cHBvcnQuc3RpdmFzb2Z0LmNvbS8=") . 'index.php?controller=Api&action=getInstall'.
                "&key=" . urlencode($licenseKey) .
                "&modulo=". urlencode(PJ_RSA_MODULO) .
                "&private=" . urlencode(PJ_RSA_PRIVATE) .
                "&server_name=" . urlencode($domain));

            $output = unserialize($response);
            if (isset($output['hash']) && isset($output['code']) && $output['code'] == 200) {
                $string = str_replace('[pj_installation]', $output['hash'], $string);
            
                if (is_writable($folder)) {
                    if (!$handle = @fopen($filename, 'wb')) {
                        $resp['code'] = 103;
                        $resp['text'] = "'app/config/config.inc.php' open fails";
                    } else {
                        if (fwrite($handle, $string) === FALSE) {
                            $resp['code'] = 102;
                            $resp['text'] = "An error occurs while writing to 'app/config/config.inc.php'";
                        } else {
                            fclose($handle);
                            $resp['code'] = 200;
                        }
                    }
                } else {
                    $resp['code'] = 101;
                    $resp['text'] = "Folder '/appointment/library/app/config/' is not writable";
                }
            } else {
                $resp['code'] = 104;
                $resp['text'] = "Security vulnerability detected";
            }
            
            printf("Code: %d\n", $resp['code']);
            if(intval($resp['code']) == 200){
                printf("Set config succcessfully.\n");
            }
            if(isset($resp['text'])){
                printf("Messsage: %s", $resp['text']);
            }
        }
    }

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
        $this->comment('Generate AS config');
        $this->generateApppointmentSchedulerConfig();
        $this->info('DONE');
    }
}
