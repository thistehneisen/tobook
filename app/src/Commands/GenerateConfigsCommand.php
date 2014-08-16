<?php namespace App\Commands;

use Config;
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
     * Generate AS local config
     */
    protected function generateApppointmentSchedulerConfig()
    {
        define('ROOT_PATH', 'Banana?');
        require_once public_path().'/appointment/core/framework/components/pjToolkit.component.php';

        $installPath =  public_path().'/appointment/';
        $filename = $installPath . '/app/config/config.inc.php';
        $sampleContent = $installPath . '/app/config/config.sample.php';
        $folder = $installPath . '/app/config/';
        $installFolder = '/appointment/';
        $licenseKey = 'a6bf7b1eb56f9b7a0a0a9ed4acaca1a4';
        $rsaModulo = '1481520313354086969195005236818182195268088406845365735502215319550493699869327120616729967038217547';
        $rsaPrivate = '7';

        if (!file_exists($filename)) {
            $this->comment('Generate AS config');
            $domain = $this->ask('What is your local domain name?');
            $installUrl = 'http://' . $domain . $installFolder;

            $varaaDb = Config::get('database.connections.mysql');
            $string = file_get_contents($sampleContent);
            $string = str_replace('[hostname]', $varaaDb['host'], $string);
            $string = str_replace('[username]', $varaaDb['username'], $string);
            $string = str_replace('[password]', $varaaDb['password'], $string);
            $string = str_replace('[database]', $varaaDb['database'], $string);

            $string = str_replace('[install_folder]', $installFolder, $string);
            $string = str_replace('[install_path]', $installPath, $string);
            $string = str_replace('[install_url]', $installUrl, $string);
            $string = str_replace('[salt]', \pjToolkit::getRandomPassword(8), $string);

            $response = file_get_contents('http://support.stivasoft.com/index.php?controller=Api&action=getInstall'.
                "&key=" . urlencode($licenseKey) .
                "&modulo=". urlencode($rsaModulo) .
                "&private=" . urlencode($rsaPrivate) .
                "&server_name=" . urlencode($domain));

            $output = unserialize($response);
            if (isset($output['hash']) && isset($output['code']) && $output['code'] == 200) {
                $string = str_replace('[pj_installation]', $output['hash'], $string);

                if (is_writable($folder)) {
                    if (!$handle = @fopen($filename, 'wb')) {
                        $resp['code'] = 103;
                        $resp['text'] = "'public/appointment/app/config/config.inc.php' open fails";
                    } else {
                        if (fwrite($handle, $string) === FALSE) {
                            $resp['code'] = 102;
                            $resp['text'] = "An error occurs while writing to 'public/appointment/app/config/config.inc.php'";
                        } else {
                            fclose($handle);
                            $resp['code'] = 200;
                        }
                    }
                } else {
                    $resp['code'] = 101;
                    $resp['text'] = "Folder 'public/appointment/app/config/' is not writable";
                }
            } else {
                $resp['code'] = 104;
                $resp['text'] = "Security vulnerability detected";
            }

            $this->info('Code: '.$resp['code']);
            if (intval($resp['code']) === 200) {
                $this->info('Set AS config succcessfully.');
            }
            if (isset($resp['text'])) {
                $this->info('Messsage: '.$resp['text']);
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
        $this->generateApppointmentSchedulerConfig();
        $this->info('DONE');
    }
}
