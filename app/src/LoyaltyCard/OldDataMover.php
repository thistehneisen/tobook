<?php namespace App\LoyaltyCard;

use DB, Carbon\Carbon, Log;
use Consumer;
use App\Core\Models\User;
use App\LoyaltyCard\Models\Offer;
use App\LoyaltyCard\Models\Voucher;
use App\LoyaltyCard\Models\Transaction;
use App\LoyaltyCard\Models\Consumer as LcConsumer;

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

        // Check if old data existed
        $blogId = null;
        try {
            $row = $this->db->table('blogs')
                ->where('domain', 'LIKE', '%'.$username.'%')
                ->first();
            $blogId = $row->blog_id;
        } catch (\Exception $ex) {
            Log::error('It seems there is no existing tables for user '.$oldUserId);

            $job->delete();
            return;
        }

        if ($blogId === null) {
            Log::error('Cannot find the blog ID of user '.$oldUserId);

            $job->delete();
            return;
        }

        // Start moving data
        $this->moveCustomers($blogId, $user);
        $this->moveStamps($blogId, $user);
        $this->movePoints($blogId, $user);
        $this->moveStampHistory($blogId, $user);
        $this->movePointHistory($blogId, $user);

        // Done the job
        $job->delete();
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
        Log::info('Moving customers of '.$user->email);

        $now = Carbon::now();

        $table = $oldUserId.'_sm_clients';
        $tableConsumer = with(new Consumer())->getTable();

        // Select all customers having email
        $result = $this->db->table($table)
            ->select('users.ID AS wpId', $table.'.*')
            ->leftJoin('users', 'users.user_email', '=', $table.'.email')
            ->where('email', '!=', '')
            ->get();
        foreach ($result as $item) {
            // If the current email has been added, we'll skip
            if (isset($this->consumerMap[$item->email])) {
                continue;
            }

            // For safety
            $item->email = trim($item->email);

            // Check if there's a consumer with this email
            $consumerId = Consumer::where('email', $item->email)
                    ->pluck('id');
            if ($consumerId === null) {
                $phone = !empty($item->telephone)
                    ? $item->telephone
                    : $item->mobile;

                // Create a new consumer
                $consumerId = DB::table($tableConsumer)->insertGetId([
                    'first_name' => $item->name,
                    'last_name'  => $item->lastname,
                    'email'      => $item->email,
                    'phone'      => $phone,
                    'address'    => $item->addressLine1,
                    'city'       => $item->city,
                    'postcode'   => $item->postalcode,
                    'country'    => $item->country,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            // link to current user
            try {
                $user->consumers()->attach($consumerId);
            } catch (\Exception $ex) {}
            // Map email and old ID to new consumer ID
            $this->consumerMap[$item->email] = $consumerId;
            // We will user wpId since this is the ID used in stamp and point
            // history tables
            $this->consumerMap[$item->wpId] = $consumerId;
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
        Log::info('Move stamp campaigns of user '.$user->email);

        $table = $oldUserId.'_stamps';

        $result = [];
        try {
            $result = $this->db->table($table)->get();
        } catch (\Illuminate\Database\QueryException $ex) {
            Log::error('User did not have table _stamps', ['user' => $user->email]);
            return;
        }
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
     * Auxilary method to move stamp data to new system
     *
     * @param int                  $oldUserId
     * @param App\Core\Models\User $user
     *
     * @return void
     */
    protected function moveStampHistory($oldUserId, $user)
    {
        Log::info('Move stamp history of user '.$user->email);
        $data = [];

        // Use to collect total stamps of a consumer
        $collector = [];

        // Get all stamp history of this user
        $table = $oldUserId.'_userstamps';
        $result = [];
        try {
            $result = $this->db->table($table)->get();
        } catch (\Illuminate\Database\QueryException $ex) {
            Log::error('User did not have table _userstamps', ['user' => $user->email]);
            return;
        }
        foreach ($result as $item) {
            $consumerId = isset($this->consumerMap[$item->userid])
                ? $this->consumerMap[$item->userid]
                : null;

            // Skip if we cannot find the new corresponding consumer ID
            if ($consumerId === null
                || !isset($this->stampMap[$item->stmpid])) {
                continue;
            }

            $offerId = $this->stampMap[$item->stmpid];
            $time = Carbon::createFromTimeStamp($item->lastdate);
            $data[] = [
                'user_id'     => $user->id,
                'consumer_id' => $consumerId,
                'voucher_id'  => null,
                'offer_id'    => $offerId,
                'point'       => 0,
                'stamp'       => $item->currstm,
                'created_at'  => $time,
                'updated_at'  => $time,
            ];

            // Collect total stamps
            $collector[$consumerId] = !isset($collector[$consumerId])
                ? []
                : $collector[$consumerId];

            if (!isset($collector[$consumerId][$offerId])) {
                $collector[$consumerId][$offerId] = 0;
            }

            $collector[$consumerId][$offerId] += (int) $item->currstm;
        }

        if (!empty($data)) {
            DB::table(with(new Transaction())->getTable())
                ->insert($data);
        }

        // Update lc_consumers total stamps
        foreach ($collector as $consumerId => $totalStamps) {
            $consumer = LcConsumer::where('consumer_id', $consumerId)->first();
            if (!$consumer) {
                $consumer = new LcConsumer();
                $consumer->consumer_id = $consumerId;
                $consumer->user()->associate($user);
            }
            $consumer->total_stamps = json_encode($totalStamps);
            $consumer->save();
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
        Log::info('Move point campaigns of '.$user->email);

        $table = $oldUserId.'_giftavailable';
        $result = [];
        try {
            $result = $this->db->table($table)->get();
        } catch (\Illuminate\Database\QueryException $ex) {
            Log::error('User did not have table _giftavailable', ['user' => $user->email]);
            return;
        }
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
     * Move point histories of a user
     *
     * @param int                  $oldUserId
     * @param App\Core\Models\user $user
     *
     * @return void
     */
    protected function movePointHistory($oldUserId, $user)
    {
        Log::info('Move point history of '.$user->email);

        $data = [];

        // Use to calculate the total points a customer has
        $collector = [];
        $rows = [];

        try {
            $rows = $this->db->table($oldUserId.'_usergift')
                ->where('userid', $oldUserId)
                ->get();
        } catch (\Illuminate\Database\QueryException $ex) {
            Log::error('User did not have table _usergift', ['user' => $user->email]);

            return;
        }

        foreach ($rows as $row) {
            $consumerId = isset($this->consumerMap[$row->userid])
                ? $this->consumerMap[$row->userid]
                : null;

            if ($consumerId === null
                || !isset($this->pointMap[$row->idgift])) {
                continue;
            }

            $time = Carbon::createFromTimeStamp($row->timewon);
            $data[] = [
                'user_id'     => $user->id,
                'consumer_id' => $consumerId,
                'voucher_id'  => $this->pointMap[$row->idgift],
                'offer_id'    => null,
                'point'       => $row->point,
                'stamp'       => 0,
                'created_at'  => $time,
                'updated_at'  => $time,
            ];

            // Calculate the total point
            $collector[$consumerId] = !isset($collector[$consumerId])
                ? 0
                : $collector[$consumerId];
            $collector[$consumerId] += $point;
        }

        if (!empty($data)) {
            DB::table(with(new Transaction())->getTable())
                ->insert($data);
        }

        // Update lc_consumer data
        foreach ($collector as $consumerId => $totalPoints) {
            $consumer = LcConsumer::where('consumer_id', $consumerId)->first();
            if (!$consumer) {
                $consumer = new LcConsumer();
                $consumer->consumer_id = $consumerId;
                $consumer->user()->associate($user);
            }
            $consumer->total_points = $totalPoints;
            $consumer->save();
        }
    }
}
