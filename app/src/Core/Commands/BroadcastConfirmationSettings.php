<?php namespace App\Core\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Core\Models\User;
use Log;

class BroadcastConfirmationSettings extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'varaa:broadcast-confirmation-settings';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Copy and broadcast confimation message from one user to all others';

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
        $options = [
            'confirm_subject_client',
            'confirm_tokens_client',
            'confirm_subject_employee',
            'confirm_tokens_employee',
            'confirm_consumer_sms_message',
            'confirm_employee_sms_message',
        ];
        $values = array();

        $userId      = $this->argument('user_id');
        $basedUser   = User::find($userId);
        $basedUserOptions = $basedUser->asOptions()->get();

        foreach ($basedUserOptions as $item) {
            if(in_array($item->key, $options)) {
                $values[$item->key] = $item->value;
            }
        }
        $users = User::where('id','!=', $userId)->get();

        foreach ($users as $user) {
            $userOptions = $user->asOptions()->get();
            Log::info('Update user settings', ['user_id' => $user->id]);
            foreach ($userOptions as $item) {
                if(in_array($item->key, $options)) {
                    $item->value = $values[$item->key];
                    $item->save();
                    Log::info(sprintf('Update key %s of user_id %s', $item->key, $user->id), ['value' => $values[$item->key]]);
                }
            }
        }
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('user_id', InputArgument::REQUIRED, 'Based User Id'),
		);
	}

}
