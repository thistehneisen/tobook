<?php namespace App\Core\Commands;

use DB, Confide;
use Illuminate\Console\Command;
use App\Core\Models\User;
use App\Core\Models\Role;
use App\Core\Models\Business;

class MoveLcCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'varaa:move-lc';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Move LC data from old database to new system';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        // @see: https://github.com/varaa/varaa/issues/281
        $users = [
            'carboys',
            'strhuolto',
            'automan',
            'tkbodybalance',
            'tammerfix',
            'scorpiobarbers',
            'koulutettuhierojamarika',
            'blackpanther',
            'studiowoman',
            'khmaarit',
            'glosspoint',
            'espoontorinpyora',
            'korsonhiussalonki',
            'cawell',
            'lahdenhiusklinikka',
            'halfblock',
            'halfblockkuopio',
            'eastasiamart',
            'sinnestore',
            'in-style',
            'bcare',
            'kauneushoitolamikkeli',
            'tittis',
            'flamingoclub',
            'vanhakassu',
            'blini',
            'khmikkeli',
        ];

        // Check if there's existing business with the given username
        // If not, create a new one
        // Then push into the queue to migrate their data
        $role = Role::user();
        foreach ($users as $username) {
            $user = User::where(DB::raw('LOWER(username)'), $username)
                ->orWhere(DB::raw('LOWER(email)'), $username.'@varaa.com')
                ->first();

            if ($user === null) {
                $this->info('Creating '.$username);

                $user = new User();
                $user->email                 = $username.'@varaa.com';
                $user->password              = $username.'n0tSoF@st';
                $user->password_confirmation = $username.'n0tSoF@st';
                if (!$user->save()) {
                    dd($user->errors()->all());
                }
                $user->attachRole($role);

                // Automatically confirm
                Confide::confirm($user->confirmation_code);

                // Create business information
                $business = new Business([
                    'name' => $username
                ]);
                $business->user()->associate($user);
                $business->save();
            }
        }
    }
}
