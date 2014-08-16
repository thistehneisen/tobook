<?php namespace Cmd;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

define("ROOT_PATH", realpath(__DIR__.'/../public/appointment/library/'));
require realpath(__DIR__.'/../public/appointment/core/framework/components/pjToolkit.component.php');
require realpath(__DIR__.'/../public/appointment/app/config/options.inc.php');

class ConfigCommand extends Command {
    protected function configure() {
        $this->setName('config')
        ->setDescription('Set up proper config for Appointment Scheduler')
        ->addArgument('domain', InputArgument::REQUIRED, 'Domain to be installed');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $config = require realpath(__DIR__.'/../config.php');

        $domain = $input->getArgument('domain');

        $installFolder = '/appointment/';
        $installPath = __DIR__. '/../public/appointment/'; // <= banana?
        $installUrl = 'http://' . $domain . $installFolder;
        $licenseKey = 'a6bf7b1eb56f9b7a0a0a9ed4acaca1a4';

        $sample = $installPath . '/app/config/config.sample.php';
        $filename = $installPath . '/app/config/config.inc.php';
        $folder = $installPath . '/app/config/';

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
