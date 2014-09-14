<?php namespace App\Appointment\Commands;

use DB, Carbon\Carbon, Closure;
use Illuminate\Console\Command;

class GetStyleOptionsCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'varaa:as-get-style';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Extract more style options from old AS';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        DB::setTablePrefix('');
        $all = DB::table('as_formstyle')->get();
        $now = Carbon::now();
        foreach ($all as $item) {
            if ($item->message) {
                preg_match_all('/#(?:[0-9a-fA-F]{6}|[0-9a-fA-F]{3})[\s;]*\n/', $item->message, $matches);

                $accentColor = isset($matches[0][0]) ? trim($matches[0][0]) : null;

                $records = [
                    ['key' => 'style_main_color', 'value' => $accentColor],
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
        }

        $this->info('Done');
    }
}
