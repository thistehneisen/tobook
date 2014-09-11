<?php namespace App\Appointment\Commands;

use DB, Carbon\Carbon, Closure, Consumer, Cache;
use Illuminate\Console\Command;

class MigrateCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'as:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate old data of AS to brand-new, shinny and buggy module';

    protected $map = [];

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        // Prefix will be set manually
        DB::setTablePrefix('');

        $this->dumpFromCache();

        $this->migrateServiceCategories();
        $this->migrateExtraServices();
        $this->migrateResources();
        $this->migrateServices();
        $this->migrateResourceService();
        $this->migrateExtraServiceService();
        $this->migrateServiceTimes();
        $this->migrateEmployees();
        $this->migrateEmployeeCustomTimes();
        $this->migrateEmployeeDefaultTime();
        $this->migrateEmployeeFreetime();
        $this->migrateEmployeeService();
        $this->migrateBookings();
        $this->migrateBookingExtraServices();
        $this->migrateBookingServices();
        $this->migrateOptions();
    }

    protected function dumpFromCache()
    {
        foreach ([
            'varaa_as_service_categories',
            'varaa_as_extra_services',
            'varaa_as_resources',
            'varaa_as_services',
            'varaa_as_service_times',
            'varaa_as_employees',
            'varaa_as_bookings',
            'varaa_as_bookings_uuid',
        ] as $key) {
            if (Cache::has($key)) {
                $this->map[$key] = Cache::get($key);
            }
        }
    }

    protected function migrateServiceCategories()
    {
        $this->migrateTable('as_services_category', 'varaa_as_service_categories', function ($item) {
            return [
                'user_id'       => $item->owner_id,
                'name'          => $item->name,
                'description'   => (string) $item->message,
                'is_show_front' => $item->show_front === 'on',
                'order'         => (int) $item->order,
            ];
        });
    }

    protected function migrateExtraServices()
    {
        $this->migrateTable('as_extra_service', 'varaa_as_extra_services', function ($item) {
            $now = Carbon::now();

            return [
                'user_id'     => $item->owner_id,
                'name'        => $item->name,
                'description' => (string) $item->message,
                'price'       => $item->price,
                'length'      => $item->length,
            ];
        });
    }

    protected function migrateResources()
    {
        $this->migrateTable('as_resources', 'varaa_as_resources', function ($item) {
            return [
                'user_id'     => $item->owner_id,
                'name'        => $item->name,
                'description' => (string) $item->message,
                'quantity'    => 1,
            ];
        });
    }

    protected function migrateServices()
    {
        $map = $this->map;
        $this->migrateTable('as_services', 'varaa_as_services', function ($item) use ($map) {
            if (!isset($map['varaa_as_service_categories'][$item->category_id])) {
                $this->comment('Skip services #'.$item->id.': Category not found');
                return;
            }

            $lang = $this->getLang($item, 'pjService', [
                'name'        => 'Untitled',
                'description' => ''
            ]);

            return [
                'category_id' => $map['varaa_as_service_categories'][$item->category_id],
                'user_id'     => $item->owner_id,
                'name'        => (string) $lang['name'],
                'price'       => $item->price,
                'length'      => $item->length,
                'before'      => $item->before,
                'during'      => $item->total,
                'after'       => $item->after,
                'description' => (string) $lang['description'],
                'is_active'   => $item->is_active,
            ];
        });
    }

    protected function migrateResourceService()
    {
        $map = $this->map;
        $this->migrateTable('as_resources_services', 'varaa_as_resource_service', function ($item) use ($map) {
            return [
                'service_id'  => $map['varaa_as_services'][$item->service_id],
                'resource_id' => $map['varaa_as_resources'][$item->resources_id],
            ];
        });
    }

    protected function migrateExtraServiceService()
    {
        $map = $this->map;
        $this->migrateTable('as_services_extra_service', 'varaa_as_extra_service_service', function ($item) use ($map) {
            if (!isset($map['varaa_as_services'][$item->service_id]) ||
                !isset($map['varaa_as_extra_services'][$item->extra_id])) {
                return;
            }

            return [
                'service_id'       => $map['varaa_as_services'][$item->service_id],
                'extra_service_id' => $map['varaa_as_extra_services'][$item->extra_id],
                'no_created'       => true
            ];
        });
    }

    protected function migrateServiceTimes()
    {
        $map = $this->map;
        $this->migrateTable('as_services_time', 'varaa_as_service_times', function ($item) use ($map) {
            if (!isset($map['varaa_as_services'][$item->foreign_id])) {
                return;
            }
            return [
                'service_id'  => $map['varaa_as_services'][$item->foreign_id],
                'price'       => $item->price,
                'length'      => $item->length,
                'before'      => $item->before,
                'during'      => $item->total,
                'after'       => $item->after,
                'description' => (string) $item->description,
            ];
        });
    }

    protected function migrateEmployees()
    {
        $map = $this->map;
        $this->migrateTable('as_employees', 'varaa_as_employees', function ($item) use ($map) {
            $lang = $this->getLang($item, 'pjEmployee', [
                'name' => 'Untitled',
            ]);

            return [
                'user_id'             => $item->owner_id,
                'name'                => $lang['name'],
                'email'               => (string) $item->email,
                'phone'               => (string) $item->phone,
                'avatar'              => (string) $item->avatar,
                'description'         => '',
                'is_subscribed_email' => $item->is_subscribed,
                'is_subscribed_sms'   => $item->is_subscribed_sms,
                'is_active'           => $item->is_active,
                'order'               => 0,
            ];
        });
    }

    protected function migrateEmployeeFreetime()
    {
        $map = $this->map;
        $this->migrateTable('as_employees_freetime', 'varaa_as_employee_freetime', function ($item) use ($map) {
            if (!isset($map['varaa_as_employees'][$item->employee_id])) {
                return;
            }
            return [
                'user_id'     => $item->owner_id,
                'employee_id' => $map['varaa_as_employees'][$item->employee_id],
                'date'        => $item->date,
                'start_at'    => Carbon::createFromTimeStamp($item->start_ts, 'UTC'),
                'end_at'      => Carbon::createFromTimeStamp($item->end_ts, 'UTC'),
                'description' => (string) $item->message,
            ];
        });
    }

    protected function migrateEmployeeDefaultTime()
    {
        $items = DB::table('as_working_times')
            ->where('type', 'employee')
            ->get();

        $now = Carbon::now();
        foreach ($items as $item) {
            $data = [];
            $map = [
                'mon' => 'monday',
                'tue' => 'tuesday',
                'wed' => 'wednesday',
                'thu' => 'thursday',
                'fri' => 'friday',
                'sat' => 'saturday',
                'sun' => 'sunday',
            ];

            $counter = 0;
            foreach ($map as $short => $day) {
                $startAt = $day.'_from';
                $endAt = $day.'_to';
                $isDayOff = $day.'_dayoff';

                if (!isset($this->map['varaa_as_employees'][$item->foreign_id])) {
                    $counter++;
                    continue;
                }

                $data[] = [
                    'employee_id' => $this->map['varaa_as_employees'][$item->foreign_id],
                    'type'        => $short,
                    'start_at'    => $item->$startAt,
                    'end_at'      => $item->$endAt,
                    'is_day_off'  => $item->$isDayOff === 'T',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ];
            }

            DB::table('varaa_as_employee_default_time')->insert($data);
        }

        $this->info('as_working_times ---> varaa_as_employee_default_time');
        $this->comment('Skipped '.$counter);
    }

    protected function migrateEmployeeCustomTimes()
    {
        $items = DB::table('as_employees_custom_times')
            ->join('as_custom_times', 'as_custom_times.id', '=', 'as_employees_custom_times.customtime_id')
            ->get();

        $counter = 0;
        foreach ($items as $item) {
            if (!isset($this->map['varaa_as_employees'][$item->employee_id])) {
                $counter++;
                continue;
            }

            $data = [
                'employee_id' => $this->map['varaa_as_employees'][$item->employee_id],
                'date'        => $item->date,
                'start_at'    => $item->start_time,
                'end_at'      => $item->end_time,
                'is_day_off'  => $item->is_dayoff === 'T',
            ];

            $id = DB::table('varaa_as_employee_custom_time')->insertGetId($data);
            // $this->map['varaa_as_employee_custom_time'][$item->id] = $id;
        }

        $this->info('as_employees_custom_times ---> varaa_as_employee_custom_time');
        $this->comment('Skipped '.$counter);
    }

    protected function migrateEmployeeService()
    {
        $map = $this->map;
        $this->migrateTable('as_employees_services', 'varaa_as_employee_service', function ($item) use ($map) {
            if (!isset($map['varaa_as_employees'][$item->employee_id]) ||
                !isset($map['varaa_as_services'][$item->service_id])) {
                return;
            }

            return [
                'employee_id' => $map['varaa_as_employees'][$item->employee_id],
                'service_id'  => $map['varaa_as_services'][$item->service_id],
                'plustime'    => (int) $item->plustime,
                'no_created'  => true
            ];
        });
    }

    protected function migrateBookings()
    {
        $items = DB::table('as_bookings')
            ->join('as_bookings_services', 'as_bookings.id', '=', 'as_bookings_services.booking_id')
            ->select(
                'as_bookings.*',
                'as_bookings_services.employee_id',
                'as_bookings_services.date',
                'as_bookings_services.start'
            )
            ->get();
        $now = Carbon::now();

        $emptyEmail = 0;
        $counter = 0;
        foreach ($items as $item) {
            if (!isset($this->map['varaa_as_employees'][$item->employee_id])) {
                $counter++;
                continue;
            }

            // If empty email, create a good one, hoho
            if (empty($item->c_email)) {
                $item->c_email = 'asconsumer_'.($emptyEmail++).'@varaa.com';
            }

            // @todo: Fix consumer first_name and last_name
            try {
                $consumerId = DB::table('varaa_consumers')
                    ->insertGetId([
                        'first_name' => (string) $item->c_name,
                        'last_name'  => '',
                        'email'      => (string) $item->c_email,
                        'phone'      => (string) $item->c_phone,
                        'address'    => (string) $item->c_address_1,
                        'city'       => (string) $item->c_city,
                        'postcode'   => (string) $item->c_zip,
                        'country'    => (string) $item->c_country_id,
                        'created_at' => $item->created,
                        'updated_at' => $item->created,
                    ]);

                DB::table('varaa_consumer_user')->insert([
                    'consumer_id' => $consumerId,
                    'user_id'     => $item->owner_id,
                    'is_visible'  => 1
                ]);
            } catch (\Illuminate\Database\QueryException $ex) {
                $consumer = DB::table('varaa_consumers')
                    ->where('email', $item->c_email)
                    ->first();
                if ($consumer === null) {
                    throw $ex;
                }

                $consumerId = $consumer->id;
            }

            // Create AS consumer
            DB::table('varaa_as_consumers')->insert([
                'consumer_id' => $consumerId,
                'user_id'     => $item->owner_id,
                'created_at'  => $item->created,
                'updated_at'  => $item->created,
            ]);

            $map = [
                'confirmed' => 1,
                'pending'   => 2,
                'cancelled' => 3,
            ];

            $data = [
                'uuid'        => $item->uuid,
                'user_id'     => $item->owner_id,
                'consumer_id' => $consumerId,
                'employee_id' => $this->map['varaa_as_employees'][$item->employee_id],
                'date'        => $item->date,
                'total'       => (int) $item->booking_total,
                'modify_time' => 0,
                'start_at'    => new Carbon($item->date.' '.$item->start),
                'end_at'      => '',
                'status'      => $map[$item->booking_status],
                'ip'          => (string) $item->ip,
                'created_at'  => $item->created,
                'updated_at'  => $item->created,
            ];

            $id = DB::table('varaa_as_bookings')->insertGetId($data);

            // Map ID
            $this->map['varaa_as_bookings'][$item->id] = $id;

            // Map UUID
            $this->map['varaa_as_bookings_uuid'][$item->uuid] = $id;

            echo '.';
        }
        Cache::forever('varaa_as_bookings', $this->map['varaa_as_bookings']);
        Cache::forever('varaa_as_bookings_uuid', $this->map['varaa_as_bookings_uuid']);

        $this->info('as_bookings ---> varaa_as_bookings');
        $this->info('Skipped '.$counter);
        $this->info('Skipped empty email '.$emptyEmail);
    }

    protected function migrateBookingExtraServices()
    {
        $map = $this->map;
        $this->migrateTable('as_bookings_extra_service', 'varaa_as_booking_extra_services', function ($item) use ($map) {
            if (!isset($map['varaa_as_bookings'][$item->booking_id]) ||
                !isset($map['varaa_as_extra_services'][$item->extra_id])) {
                return;
            }
            return [
                'booking_id'       => $map['varaa_as_bookings'][$item->booking_id],
                'extra_service_id' => $map['varaa_as_extra_services'][$item->extra_id],
                'date'             => $item->date,
            ];
        });
    }

    protected function migrateBookingServices()
    {
        $from = 'as_bookings_services';
        $to = 'varaa_as_booking_services';

        $items = DB::table($from)
            ->where('booking_id', '!=', 0)
            ->whereNotNull('employee_id')
            ->get();

        $data = [];
        $now = Carbon::now();
        $counter = 0;
        foreach ($items as $item) {
            if (!isset($this->map['varaa_as_bookings'][$item->booking_id]) ||
                !isset($this->map['varaa_as_employees'][$item->employee_id]) ||
                !isset($this->map['varaa_as_services'][$item->service_id])) {
                $counter++;
                continue;
            }

            $value = [
                'tmp_uuid'          => (string) $item->tmp_hash,
                'user_id'           => $item->owner_id,
                'booking_id'        => $this->map['varaa_as_bookings'][$item->booking_id],
                'service_id'        => $this->map['varaa_as_services'][$item->service_id],
                'service_time_id'   => null,
                'employee_id'       => $this->map['varaa_as_employees'][$item->employee_id],
                'modify_time'       => 0,
                'date'              => $item->date,
                'start_at'          => new Carbon($item->date.' '.$item->start),
                'end_at'            => '',
                'is_reminder_email' => $item->reminder_email,
                'is_reminder_sms'   => $item->reminder_sms,
            ];

            if (!isset($value['no_created'])) {
                $value['created_at'] = $now;
                $value['updated_at'] = $now;
            } else {
                unset($value['no_created']);
            }

            $id = DB::table($to)->insertGetId($value);
            $this->map[$to][$item->id] = $id;
        }

        $this->info($from.' ---> '.$to);
        $this->info('Skipped '.$counter);

        // Store to cache
        Cache::forever($to, $this->map[$to]);
    }

    protected function migrateInvoices()
    {
        $map = $this->map;
        $this->migrateTable('as_plugin_invoice', 'varaa_as_invoices', function ($item) use ($map) {
            $bookingId = $this->map['varaa_as_bookings_uuid'][$item->order_id];

            return [
                'booking_id'       => $bookingId,
                'issue_date'       => $item->issue_date,
                'due_date'         => $item->due_date,
                'total'            => $item->total,
                'discount'         => $item->discount,
                'deposit'          => $item->paid_deposit,
                'amount_due'       => $item->amount_due,
                'shipping_cost'    => $item->shipping,
                'tax'              => $item->tax,
                'currency'         => $item->currency,
                'billing_address'  => (string) $item->b_billing_address,
                'billing_name'     => (string) $item->b_name,
                'billing_phone'    => (string) $item->b_phone,
                'billing_zip'      => (string) $item->b_zip,
                'shipping_address' => (string) $item->s_shipping_address,
                'shipping_name'    => (string) $item->s_name,
                'shipping_phone'   => (string) $item->s_phone,
                'shipping_zip'     => (string) $item->s_zip,
                'is_shipped'       => $item->s_is_shipped,
                'notes'            => (string) $item->notes,
                'status'           => $item->status,
                'created_at'       => $item->created,
                'updated_at'       => $item->modified,
                'no_created'       => true,
            ];
        });
    }

    protected function migrateOptions()
    {
        // Migrate FE styles
        $all = DB::table('as_formstyle')->get();
        $now = Carbon::now();
        foreach ($all as $item) {
            $records = [
                ['key' => 'style_logo', 'value' => $item->logo],
                ['key' => 'style_banner', 'value' => $item->banner],
                ['key' => 'style_heading_color', 'value' => $item->color],
                ['key' => 'style_color', 'value' => $item->color],
                ['key' => 'style_background', 'value' => $item->background],
                ['key' => 'style_custom_css', 'value' => $item->message],
            ];

            $data = [];
            foreach ($records as $r) {
                $r['name']       = '';
                $r['value']      = json_encode($r['value']);
                $r['created_at'] = $now;
                $r['updated_at'] = $now;
                $r['is_visible'] = true;
                $r['user_id']    = $item->owner_id;

                $data[] = $r;
            }

            DB::table('varaa_as_options')->insert($data);
        }

        // Migrate default working time
        $all = DB::table('as_working_times')->where('type', 'calendar')->get();
        foreach ($all as $item) {
            $records = [];

            $data = [
                'mon' => ['start' => $item->monday_from, 'end' => $item->monday_to],
                'tue' => ['start' => $item->tuesday_from, 'end' => $item->tuesday_to],
                'wed' => ['start' => $item->wednesday_from, 'end' => $item->wednesday_to],
                'thu' => ['start' => $item->thursday_from, 'end' => $item->thursday_to],
                'fri' => ['start' => $item->friday_from, 'end' => $item->friday_to],
                'sat' => ['start' => $item->saturday_from, 'end' => $item->saturday_to],
                'sun' => ['start' => $item->sunday_from, 'end' => $item->sunday_to],
            ];

            DB::table('varaa_as_options')->insert([
                'key'        => 'working_time',
                'name'       => '',
                'value'      => json_encode($data),
                'created_at' => $now,
                'updated_at' => $now,
                'is_visible' => true,
                'user_id'    => $item->owner_id,
            ]);
        }

        // Migrate confirmation email
        $all = DB::table('as_multi_lang')
            ->where('model', 'pjCalendar')
            ->whereIn('field', [
                'confirm_subject_client',
                'confirm_tokens_client',
                'confirm_subject_employee',
                'confirm_tokens_employee',
                'confirm_subject_admin',
                'confirm_tokens_admin',
            ])->get();

        $data = [];
        foreach ($all as $item) {
            $data[] = [
                'key'        => $item->field,
                'name'       => '',
                'value'      => json_encode($item->content),
                'created_at' => $now,
                'updated_at' => $now,
                'is_visible' => true,
                'user_id'    => $item->owner_id,
            ];
        }
        DB::table('varaa_as_options')->insert($data);

        $this->info('as_formstyle ---> varaa_as_options');
        $this->info('as_working_times ---> varaa_as_options');
    }

    protected function getLang($item, $model, $default)
    {
        $result = DB::table('as_multi_lang')
            ->where('model', $model)
            ->where('owner_id', $item->owner_id)
            ->where('foreign_id', $item->id)
            ->get();

        if ($result) {
            $default = array_combine(array_pluck($result, 'field'), array_pluck($result, 'content'));
        }

        return $default;
    }

    protected function migrateTable($from, $to, Closure $mapping)
    {
        $this->info($from.' ---> '.$to);
        $items = DB::table($from)->get();

        $data = [];
        $now = Carbon::now();
        $counter = 0;

        foreach ($items as $item) {
            $value = call_user_func($mapping, $item);
            if ($value === null) {
                $counter++;
                // Skip
                continue;
            }

            if (!isset($value['no_created'])) {
                $value['created_at'] = $now;
                $value['updated_at'] = $now;
            } else {
                unset($value['no_created']);
            }

            $id = DB::table($to)->insertGetId($value);
            $this->map[$to][$item->id] = $id;
        }

        $this->info('Done');
        if ($counter > 0) {
            $this->comment('Skipped: '.$counter);
        }

        // Store to cache
        Cache::forever($to, $this->map[$to]);
    }
}
