<?php namespace App\LoyaltyCard\Commands;
use DB, Validator;
use Illuminate\Console\Command;
use App\LoyaltyCard\Models\Offer as OfferModel;
use App\LoyaltyCard\Models\Voucher as VoucherModel;
use App\LoyaltyCard\Models\Consumer as ConsumerModel;
use App\Consumers\Models\Consumer as Core;
use App\LoyaltyCard\Models\Transaction as TransactionModel;

class MigrateLoyaltyCardCommand extends Command
{
    protected $name = 'varaa:move-lc';
    protected $description = 'Move loyalty card information from old tables to new one';
    protected $oldPrefix;
    protected $totalStamps;
    protected $totalUsageOfVoucher;
    protected $totalUsageOfOffer;

    public function __construct()
    {
        parent::__construct();
    }

    public function fire()
    {
        $this->oldPrefix = DB::getTablePrefix();
        $this->totalStamps = [];
        $this->totalUsageOfOffer = [];
        $this->totalUsageOfVoucher = [];

        $this->moveVouchers();
        $this->moveOffers();
        $this->moveConsumers();
        $this->makeVoucherTransactions();
        $this->makeOfferTransactions();

        $this->info('FINISHED');
    }

    private function updateConsumerStamp()
    {
        $this->info('Update total stamps to consumers');
        foreach ($this->totalStamps as $key => $value) {
            $consumerTotalStamps = json_encode($this->totalStamps[$key]);

            $consumer = ConsumerModel::find($key);

            if ($consumer) {
                $consumer->total_stamps = $consumerTotalStamps;
                $consumer->save();
            }
        }
        $this->info('DONE');
    }

    private function updateOfferUsedTime()
    {
        $this->info('Update total used time of offers');
        foreach ($this->totalUsageOfOffer as $key => $value) {
            $offer = OfferModel::find($key);

            if ($offer) {
                $offer->total_used = $value;
                if ($offer->save()) {
                    echo "\t{$offer->name} used {$value} times\t\t";
                    $this->info('DONE');
                }
            }
        }
    }

    private function updateVoucherUsedTime()
    {
        $this->info('Update total used time of vouchers');
        foreach ($this->totalUsageOfVoucher as $key => $value) {
            $voucher = VoucherModel::find($key);

            if ($voucher) {
                $voucher->total_used = $value;
                if ($voucher->save()) {
                    echo "\t{$voucher->name} used {$value} times\t\t";
                    $this->info('DONE');
                }
            }
        }
    }

    private function makeVoucherTransactions()
    {
        DB::setTablePrefix('');
        $result = DB::table('tbl_loyalty_consumer_point')->get();
        $this->info('Making voucher transactions');
        DB::setTablePrefix($this->oldPrefix);

        foreach ($result as $item) {
            $voucher = VoucherModel::find($item->loyalty_point);
            $consumer = ConsumerModel::find($item->loyalty_consumer);

            if ($voucher && $consumer) {
                $transaction = new TransactionModel;
                $transaction->user_id = $voucher->user_id;
                $transaction->consumer_id = $item->loyalty_consumer;
                $transaction->voucher_id = $item->loyalty_point;
                $transaction->point = $voucher->required * -1;
                $transaction->save();

                if (array_key_exists($voucher->id, $this->totalUsageOfVoucher)) {
                    $this->totalUsageOfVoucher[$voucher->id] += 1;
                } else {
                    $this->totalUsageOfVoucher[$voucher->id] = 1;
                }
            }
        }

        $this->info('DONE');

        $this->updateVoucherUsedTime();
    }

    private function makeOfferTransactions()
    {
        DB::setTablePrefix('');
        $result = DB::table('tbl_loyalty_consumer_stamp')->get();
        $this->info('Making offer transactions');
        DB::setTablePrefix($this->oldPrefix);

        foreach ($result as $item) {
            $offer = OfferModel::find($item->loyalty_stamp);
            $consumer = ConsumerModel::find($item->loyalty_consumer);

            if ($offer && $consumer) {
                $transaction = new TransactionModel;
                $transaction->user_id = $offer->user_id;
                $transaction->consumer_id = $item->loyalty_consumer;
                $transaction->offer_id = $item->loyalty_stamp;
                $transaction->stamp = $item->cnt_used + $item->cnt_free;
                $transaction->save();

                if (array_key_exists($offer->id, $this->totalUsageOfOffer)) {
                    $this->totalUsageOfOffer[$offer->id] += 1;
                } else {
                    $this->totalUsageOfOffer[$offer->id] = 1;
                }

                $this->totalStamps[$item->loyalty_consumer][$offer->id] = $item->cnt_used + $item->cnt_free;
            }
        }

        $this->info('DONE');

        $this->updateOfferUsedTime();

        $this->updateConsumerStamp();
    }

    private function moveConsumers()
    {
        DB::setTablePrefix('');

        $result = DB::table('tbl_loyalty_consumer')->get();
        $this->info('Moving '.count($result).' consumers to the new tables');

        DB::setTablePrefix($this->oldPrefix);

        $emailCounter = 1;

        foreach ($result as $item) {
            $validator = Validator::make(['email' => $item->email], ['email' => 'required|email']);

            if ($validator->fails()) {
                $item->email = 'consumer' . $emailCounter . '@varaa.com';
                $emailCounter += 1;
            }

            $existedConsumer = ConsumerModel::join('consumers', 'lc_consumers.consumer_id', '=', 'consumers.id')
                                        ->where('consumers.email', '=', $item->email)
                                        ->first();

            if(!$existedConsumer) {
                $core = new Core;
                $consumer = new ConsumerModel;
                $core->unguard();
                $consumer->unguard();
                $core->fill([
                    'id'            => $item->loyalty_consumer,
                    'first_name'    => $item->first_name,
                    'last_name'     => $item->last_name,
                    'email'         => $item->email,
                    'phone'         => $item->phone,
                    'address'       => $item->address1,
                    'postcode'      => '',
                    'city'          => $item->city,
                    'country'       => '',
                    'created_at'    => $item->created_time,
                    'updated_at'    => $item->updated_time,
                ]);

                $consumer->fill([
                    'id'            => $item->loyalty_consumer,
                    'consumer_id'   => $item->loyalty_consumer,
                    'total_points'  => $item->current_score,
                    'total_stamps'  => '',
                    'created_at'    => $item->created_time,
                    'updated_at'    => $item->updated_time,
                ]);

                $core->reguard();
                $consumer->reguard();

                if ($core->save() && $consumer->save()) {
                    echo "\t{$item->email} new\t\t";
                    $this->info('ADDED TO ALL CONSUMER TABLES');
                }

                DB::table('consumer_user')->insert([
                    'consumer_id'   => $item->loyalty_consumer,
                    'user_id'       => $item->owner,
                    'is_visible'    => 1,
                ]);
            } else {
                echo "\t{$item->email} duplicated\t\t";

                try {
                    $consumer = new ConsumerModel;
                    $consumer->unguard();

                    $consumer->fill([
                        'id'            => $item->loyalty_consumer,
                        'consumer_id'   => $existedConsumer->id,
                        'total_points'  => $item->current_score,
                        'total_stamps'  => '',
                        'created_at'    => $item->created_time,
                        'updated_at'    => $item->updated_time,
                    ]);

                    $consumer->reguard();

                    if ($consumer->save()) {
                        $this->info('ADDED TO LC CONSUMER TABLES');
                    }

                    DB::table('consumer_user')->insert([
                        'consumer_id'   => $existedConsumer->id,
                        'user_id'       => $item->owner,
                        'is_visible'    => 1,
                    ]);
                } catch (\Illuminate\Database\QueryException $ex) {
                    $this->error('Error: ' . $ex->getMessage());
                }

            }
        }
    }

    private function moveVouchers()
    {
        $totalUsageOfVoucher = [];
        DB::setTablePrefix('');
        $result = DB::table('tbl_loyalty_point')->get();
        $this->info('Moving '.count($result).' vouchers to the new tables');

        DB::setTablePrefix($this->oldPrefix);

        foreach ($result as $item) {
            $voucher = new VoucherModel;
            $voucher->unguard();

            $voucher->fill([
                'id'            => $item->loyalty_point,
                'user_id'       => $item->owner,
                'name'          => $item->point_name,
                'required'      => $item->score_required,
                'total_used'    => 0,
                'value'         => $item->discount,
                'type'          => 'Percent',
                'is_active'     => $item->valid_yn === 'Y' ? true : false,
                'created_at'    => $item->created_time,
                'updated_at'    => $item->updated_time,
            ]);

            $voucher->reguard();

            if ($voucher->save()) {
                echo "\t{$item->point_name}\t\t";
                $this->info('DONE');
            }
        }
    }

    private function moveOffers()
    {
        $totalUsageOfOffer = [];
        DB::setTablePrefix('');
        $result = DB::table('tbl_loyalty_stamp')->get();
        $this->info('Moving '.count($result).' offers to the new tables');

        DB::setTablePrefix($this->oldPrefix);

        foreach ($result as $item) {
            $offer = new OfferModel;
            $offer->unguard();

            $offer->fill([
                'id'            => $item->loyalty_stamp,
                'user_id'       => $item->owner,
                'name'          => $item->stamp_name,
                'required'      => $item->cnt_required,
                'total_used'    => 0,
                'free_service'  => $item->cnt_free,
                'is_active'     => $item->valid_yn === 'Y' ? true : false,
                'is_auto_add'   => $item->auto_add_yn === 'Y' ? true : false,
                'created_at'    => $item->created_time,
                'updated_at'    => $item->updated_time,
                ]);

            $offer->reguard();

            if ($offer->save()) {
                echo "\t{$item->stamp_name}\t\t";
                $this->info('DONE');
            }
        }
    }

    protected function getArguments()
    {
        return [];
    }

    protected function getOptions()
    {
        return [];
    }
}
