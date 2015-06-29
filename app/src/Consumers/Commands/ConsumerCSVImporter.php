<?php namespace App\Consumers\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Core\Models\User;
use App\Consumers\Models\Group;
use App\Consumers\Models\Consumer;
use DB, Util;
use Log;

class ConsumerCSVImporter extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'varaa:consumer-csv-import';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Import consumer to cosumer hub from .csv file';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$userId    = $this->option('id');
        $fileName  = $this->option('file');
        $groupName = $this->argument('group');
        if ($userId === null || $fileName === null) {
            //@todo: Handle later
            return;
        }

        $groupMap = [
            'mp_messut_2014.csv'                            => 'MP-Messut',
            'cityrent_moottoripyora_vuokraus_18_5_2014.csv' => 'Moottoripyörän vuokraus',
            'cityrent_kaikki_18_5_2014.csv'                 => 'Loput',
        ];

        if (empty($groupName)) {
            $groupName = $groupMap[$fileName];
        }

        $user = User::findOrFail($userId);
        $group = $this->upsertGroup($groupName, $user);

        $this->doImport($fileName, $user, $group);

	}

    protected function doImport($fileName, $user, $group)
    {
        $csvRows = array_map('str_getcsv', file($fileName));
        //Remove the header rows
        array_shift($csvRows);
        foreach ($csvRows as $row) {
            $this->upsertConsumer($row, $user, $group);
        }
    }

    /**
    *   $row = [
    *       [0] => string(13) "jari pajujoki"
    *       [1] => string(18) "pajujoki@gmail.com"
    *       [2] => string(10) "0504915225"
    *   ]
    *
    *   $row = [
    *      'first_name' => string(4) "Joni"
    *      'last_name' => string(9) "siekkinen"
    *      'email' => string(20) "testerasoy@gmail.com"
    *      'phone' => string(10) "0402404830"
    *   ]
    */
    protected function upsertConsumer($row, $user, $group)
    {
        $first_name = $last_name = $email = $phone = '';

        if(count($row) === 3) {
            $name = (!empty($row[0])) ? $row[0] : 'Undefined Undefined';
            $nameElements = explode(' ', $name);

            $first_name = $nameElements[0];
            $last_name = substr($name, strlen($first_name));
            $email = (!empty($row[1])) ? $row[1] : null;
            $phone = (!empty($row[2])) ? $row[2] : null;
        } else {
            $first_name = $row[0];
            $last_name = $row[1];
            $email = (!empty($row[2])) ? $row[2] : null;
            $phone = (!empty($row[3])) ? $row[3] : null;
        }



        $first_name = (!empty(trim($first_name))) ? $first_name : 'Undefined';
        $last_name = (!empty(trim($last_name))) ? $last_name : 'Undefined';
        $email = str_replace(',', '.', $email);

        $phone = str_replace(' ', '', $phone);
        $phone = preg_replace('/[a-zA-Z-_()]/', '', $phone);
        $phone = preg_replace('/[^0-9]+/i', '', $phone);
        $phone = (!empty(trim($phone))) ? trim($phone) : '0';

        //+4561788176/0407068426
        //0451332832,0505678467
        if($phone != '0') {

            if(strpos($phone, '/') !== false) {
                $phoneElems = explode('/', $phone);
                $phone = $phoneElems[0];
            }

            if(strpos($phone, ',') !== false) {
                $phoneElems = explode(',', $phone);
                $phone = $phoneElems[0];
            }

        }

        if(strpos($email, '/') !== false) {
            $emailElems = explode('/', $email);
            $email = $emailElems[0];
        }

        // var_dump([
        //     'first_name' => $first_name,
        //     'last_name'  => $last_name,
        //     'email'      => $email,
        //     'phone'      => $phone
        // ]);

        if(empty($email)) {
            return;
        }

        $consumer = Consumer::where('email', '=', $email)->where('phone', '=', $phone)->first();

        if (empty($consumer)) {

            $first_name = mb_convert_case(trim($first_name), MB_CASE_TITLE, "UTF-8");
            $last_name  = mb_convert_case(trim($last_name), MB_CASE_TITLE, "UTF-8");

            try{
                $consumer = new Consumer();
                $consumer->fill([
                    'first_name' => $first_name,
                    'last_name'  => $last_name,
                    'email'      => $email,
                    'phone'      => $phone
                ]);
                $consumer->saveOrFail();
                $consumer->users()->attach($user->id);
             } catch(\Exception $ex){
                Log::info('Exception: ' . $ex->getMessage());
                Log::info(sprintf('email : %s - phone : %s', $email, $phone));
            }
            Log::info(sprintf("New consumer : %s \n", $email));
        }

        printf("email : %s - phone : %s \n", $email, $phone);

        try{
            $group->consumers()->attach($consumer->id);
            $consumer->saveOrFail();
        } catch(\Exception $ex){
            Log::info('Exception: ' . $ex->getMessage());
            Log::info(sprintf('email : %s - phone : %s', $email, $phone));
        }
    }

    protected function upsertGroup($groupName, $user)
    {
        if (empty($groupName)) {
            return;
        }

        $group = Group::where('name', '=', $groupName)->first();

        if (empty($group)) {
            $group = new Group([
                'name' => $groupName,
            ]);
            $group->user()->associate($user);
            $group->saveOrFail();
            Log::info(sprintf("New group : %s \n", $groupName));
        }
        return $group;
    }

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
            ['group', InputArgument::OPTIONAL, 'Group of consumer']
		];
	}

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['id', null, InputOption::VALUE_OPTIONAL, 'ID of the user'],
            ['file', null, InputOption::VALUE_OPTIONAL, 'Name of CSV']
        ];
    }

}
