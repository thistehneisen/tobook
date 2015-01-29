<?php namespace App\LoyaltyCard\Commands;

use DB, Confide, Queue;
use Illuminate\Console\Command;
use App\Core\Models\User;
use App\Core\Models\Role;
use App\Core\Models\Business;
use App\LoyaltyCard\OldDataMover;

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
            1001374 => 'blinit',
            161     => 'carboys',
            5749    => 'cawell',
            6429    => 'eastasiamart',
            4700    => 'espoontoripyora',
            1001194 => 'flamingoclub',
            3242    => 'glosspoint',
            6043    => 'halfblock',
            6051    => 'halfblockkuopio',
            7270    => 'in-style',
            1001162 => 'kauneushoitolamikkeli',
            2729    => 'khmaarit',
            1001818 => 'khmikkeli',
            5701    => 'korsonhius',
            1528    => 'koulutettuhierojamarika',
            5770    => 'lahdenhiusk',
            597     => 'scorpiobarbers',
            1002392 => 'sinnestore',
            8927    => 'strhuolto',
            1912    => 'studiowoman',
            362     => 'tammerfix',
            1001257 => 'tittis',
            324     => 'tkbodybalance',
            1001381 => 'vanhakassu',
        ];

        // Database handler to interact with old WP db
        $db = DB::connection('old');

        // Get role User first, so that we don't need to hit the database again
        $role = Role::user();
        foreach ($users as $oldId => $username) {
            // Default email
            // We will try to get the real email soon
            $email = $username.'@customers.varaa.com';

            // Go to wp_users to fetch email of this user
            $user = $db->table('users')->where('ID', $oldId)->first();
            if ($user && !empty($user->user_email)) {
                $email = $user->user_email;
            }

            // Check if there's existing business with the given username
            // Because users could login by using bot username and email, need
            // to check those two fields
            $user = User::where(DB::raw('LOWER(username)'), $username)
                ->orWhere(DB::raw('LOWER(email)'), $email)
                ->first();

            // If not, create a new one
            if ($user === null) {
                $this->info('Creating new user as: '.$email);

                $user = new User();
                $user->email                 = $email;
                $user->password              = $username.'2mfPdEiy';
                $user->password_confirmation = $username.'2mfPdEiy';
                if (!$user->save()) {
                    $this->error('Cannot create user as: '.$email);
                    continue;
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

            // Then push into the queue to migrate their data
            Queue::push('\App\LoyaltyCard\OldDataMover', [
                $username,
                $oldId,
                $user->id, // User ID in the new system
            ]);
        }
    }
}
