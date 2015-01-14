<?php namespace App\LoyaltyCard;

use DB, Carbon\Carbon, Log;
use Consumer;
use App\Core\Models\User;
use App\LoyaltyCard\Models\Offer;
use App\LoyaltyCard\Models\Voucher;
use App\LoyaltyCard\Models\Transaction;

class OldDataMover
{
    protected $userId;
    protected $db;
    protected $consumerMap = [];
    protected $stampMap = [];
    protected $pointMap = [];

    public function fire($job, $data)
    {
        list ($username, $oldUserId, $newUserId) = $data;
        $this->userId = $newUserId;

        Log::info('Moving LC data of user: '.$username);

        // Find the user
        $user = User::find($newUserId);
        if ($user === null) {
            Log::error('Cannot find user '.$newUserId, ['context' => 'Move LC data']);

            return;
        }

        // Prepare the database connection
        $this->db = DB::connection('old');

        // Start moving data
        $this->moveCustomers($oldUserId, $user);
        $this->moveStamps($oldUserId, $user);
        $this->movePoints($oldUserId, $user);
        $this->moveStampHistory($oldUserId, $user);
        $this->movePointHistory($oldUserId, $user);

        // Done the job
        // $job->delete();
    }

    /**
     * Auxilary method to move old consumers
     *
     * @param int                  $oldUserId
     * @param App\Core\Models\User $user
     *
     * @return void
     */
    protected function moveCustomers($oldUserId, $user)
    {
        Log::info('Moving customers');

        $table = $oldUserId.'_sm_clients';
        $tableConsumer = with(new Consumer())->getTable();

        $result = $this->db->table($table)->get();
        foreach ($result as $item) {
            // Skip duplicated email
            if (isset($this->consumerMap[$item->email])) {
                continue;
            }

            // Check if there's a consumer with this email
            $consumerId = Consumer::where('email', trim($item->email))
                    ->pluck('id');
            if ($consumerId === null) {
                // Create a new consumer
                $consumerId = DB::table($tableConsumer)->insertGetId([
                    'first_name' => $item->name,
                    'last_name'  => $item->lastname,
                    'email'      => $item->email,
                    'phone'      => $item->telephone,
                    'address'    => $item->addressLine1,
                    'city'       => $item->city,
                    'postcode'   => $item->postalcode,
                    'country'    => $item->country,
                ]);
            }

            // Map email and old ID to new consumer ID
            $this->consumerMap[$item->email] = $consumerId;
            $this->consumerMap[$item->id] = $consumerId;
        }

        Log::info('There are '.count($this->consumerMap).' consumers');
    }

    /**
     * Move stamp campaigns
     *
     * @param int                  $oldUserId
     * @param App\Core\Models\User $user
     *
     * @return void
     */
    public function moveStamps($oldUserId, $user)
    {
        // Move stamp campaigns
        Log::info('Move stamp campaigns...');

        $table = $oldUserId.'_stamps';
        $result = $this->db->table($table)->get();
        foreach ($result as $item) {
            // The corresponding "stamp campaigns" is "offers"
            $offer = new Offer([
                'name'        => $item->title,
                'required'    => $item->stmreq,
                'is_active'   => true,
                'is_auto_add' => true
            ]);
            $offer->user()->associate($user);
            $offer->created_at = Carbon::createFromTimeStamp($item->dateadded);
            $offer->save();

            // Map old and new ID
            $this->stampMap[$item->id] = $offer->id;
        }
    }

    /**
     * Move point campaigns
     *
     * @param int                  $oldUserId
     * @param App\Core\Models\User $user
     *
     * @return void
     */
    public function movePoints($oldUserId, $user)
    {
        Log::info('Move point campaigns...');

        $table = $oldUserId.'_giftavailable';
        $result = $this->db->table($table)->get();
        foreach ($result as $item) {
            // Likewise, "point campaigns" are "vouchers"
            $voucher = new Voucher([
                'name'      => $item->giftname,
                'required'  => $item->point,
                'value'     => $item->discount,
                'type'      => 'Percent',
                'is_active' => true,
            ]);
            $voucher->user()->associate($user);
            $voucher->save();

            // Map IDs
            $this->pointMap[$item->id] = $voucher->id;
        }
    }

    /**
     * Auxilary method to move stamp data to new system
     *
     * @param int                  $oldUserId
     * @param App\Core\Models\User $user
     *
     * @return void
     */
    protected function moveStampHistory($oldUserId, $user)
    {
        Log::info('Move stamp history');
        $now = Carbon::now();
        $data = [];

        // Get all stamp history of this user
        $table = $oldUserId.'_userstamps';
        $result = $this->db->table($table)->get();
        foreach ($result as $item) {
            $consumerId = isset($this->consumerMap[$item->userid])
                ? $this->consumerMap[$item->userid]
                : null;

            if ($consumerId === null) {
                continue;
            }

            $data[] = [
                'user_id'     => $user->id,
                'consumer_id' => $consumerId,
                'voucher_id'  => null,
                'offer_id'    => $this->stampMap[$item->stmpid],
                'point'       => 0,
                'stamp'       => $item->currstm,
                'created_at'  => $now,
                'updated_at'  => $now,
            ];
        }

        if (!empty($data)) {
            DB::table(with(new Transaction())->getTable())
                ->insert($data);
        }
    }

    /**
     * Move point histories of a user
     *
     * @param int                  $oldUserId
     * @param App\Core\Models\user $user
     *
     * @return void
     */
    protected function movePointHistory($oldUserId, $user)
    {
        Log::info('Move point history');

        $now = Carbon::now();
        $data = [];

        $rows = $this->db->table('userpointhistory')
            ->where('idblog', $oldUserId)
            ->get();
        foreach ($rows as $row) {
            $consumerId = isset($this->consumerMap[$row->userid])
                ? $this->consumerMap[$row->userid]
                : null;

            if ($consumerId === null) {
                continue;
            }

            $data[] = [
                'user_id'     => $user->id,
                'consumer_id' => $consumerId,
                'voucher_id'  => $this->pointMap[$row->idgift],
                'offer_id'    => null,
                'point'       => $this->point,
                'stamp'       => 0,
                'created_at'  => $now,
                'updated_at'  => $now,
            ];
        }

        if (!empty($data)) {
            DB::table(with(new Transaction())->getTable())
                ->insert($data);
        }
    }
}
