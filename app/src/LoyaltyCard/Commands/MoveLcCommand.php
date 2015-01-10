<?php namespace App\LoyaltyCard\Commands;

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
            217     => 'automan',
            9593    => 'bcare',
            1470    => 'blackpanther',
            1001374 => 'blini',
            161     => 'carboys',
            5749    => 'cawell',
            6429    => 'eastasiamart',
            4700    => 'espoontorinpyora',
            1001194 => 'flamingoclub',
            3242    => 'glosspoint',
            6043    => 'halfblock',
            6051    => 'halfblockkuopio',
            7270    => 'in-style',
            1001162 => 'kauneushoitolamikkeli',
            2729    => 'khmaarit',
            1001818 => 'khmikkeli',
            5701    => 'korsonhiussalonki',
            1528    => 'koulutettuhierojamarika',
            5770    => 'lahdenhiusklinikka',
            597     => 'scorpiobarbers',
            1002392 => 'sinnestore',
            8927    => 'strhuolto',
            1912    => 'studiowoman',
            362     => 'tammerfix',
            1001257 => 'tittis',
            324     => 'tkbodybalance',
            1001381 => 'vanhakassu',
        ];

        // Check if there's existing business with the given username
        // If not, create a new one
        // Then push into the queue to migrate their data
        $role = Role::user();
        foreach ($users as $oldId => $username) {
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
