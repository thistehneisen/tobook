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
        $table = $oldUserId.'_sm_clients';
        $result = $this->db->table($table)->get();
        foreach ($result as $item) {
            // Check if there's a consumer with this email
            $consumer = Consumer::where('email', trim($item->email))->find();
            if ($consumer === null) {
                // Create a new consumer
                $consumer = Consumer::make([
                    'first_name' => $item->name,
                    'last_name'  => $item->lastname,
                    'email'      => $item->email,
                    'phone'      => $item->telephone,
                    'address'    => $item->addressLine1,
                    'city'       => $item->city,
                    'postcode'   => $item->postalcode,
                    'country'    => $item->country,
                ], $user);
            }

            $this->consumerMap[$item->id] = $consumer->id;
        }
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
        Log::info('Moving stamp campaigns...');

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
            $offer->user = $user;
            $offer->created_at = new Carbon($item->dateadded);
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
        Log::info('Moving point campaigns...');

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
            $voucher->user = $user;
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

        DB::table(
            with(new Transaction())->getTable()
        )->insert($data);
    }
}
