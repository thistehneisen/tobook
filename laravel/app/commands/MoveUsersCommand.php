<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class MoveUsersCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'varaa:move-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Move users of old tables to new one';

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
        // No need to send activation emails
        Config::set('confide::signup_confirm', false);

        // Because tbl_user_mast has its own prefix, so we disable db prefix here
        $oldPrefix = DB::getTablePrefix();
        DB::setTablePrefix('');

        // Get all users
        $result = DB::table('tbl_user_mast')->get();
        $this->info('Moving '.count($result).' users to the new home...');

        // Set the prefix back to normal
        DB::setTablePrefix($oldPrefix);
        foreach ($result as $item) {
            $user = new User();
            $user->unguard();
            $user->fill([
                'username'              => $item->vuser_login,
                'email'                 => $item->vuser_email,
                'old_password'          => $item->vuser_password,
                'password'              => '123456',
                'password_confirmation' => '123456',
                'confirmation_code'     => '',
                'remember_token'        => '',
                'confirmed'             => 1,
                'first_name'            => $item->vuser_name,
                'last_name'             => $item->vuser_lastname,
                'address_1'             => $item->vuser_address1,
                'address_2'             => $item->vuser_address2,
                'city'                  => $item->vcity,
                'state'                 => $item->vstate,
                'zipcode'               => $item->vzip,
                'country'               => $item->vcountry,
                'phone'                 => $item->vuser_phone,
                'fax'                   => $item->vuser_fax,
                'stylesheet'            => $item->vuser_style,
                'created_at'            => $item->duser_join
            ]);
            $user->reguard();

            if ($user->save()) {
                echo "\t{$item->vuser_email}\t\t";
                $this->info('DONE');
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
        return [];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [];
    }

}
