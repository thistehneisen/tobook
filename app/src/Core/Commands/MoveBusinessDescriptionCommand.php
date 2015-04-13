<?php namespace App\Core\Commands;

use App\Core\Models\Business;
use App\Core\Models\Multilanguage;
use Config;
use Illuminate\Console\Command;

class MoveBusinessDescriptionCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'varaa:move-business-description';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Move business description to target language';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $defaultLanguage = Config::get('varaa.default_language');
        $businesses = Business::with('user')->where('description', '!=', '')->get();
        foreach ($businesses as $business) {
            $multilang = Multilanguage::where('context', $business->getTable())
                ->where('key', 'business_description')
                ->where('lang', $defaultLanguage)
                ->where('user_id', $business->user_id)
                ->first();

            if ($multilang === null) {
                $multilang = new Multilanguage([
                    'context' => $business->getTable(),
                    'key'     => 'business_description',
                    'lang'    => $defaultLanguage,
                    'value'   => $business->getOriginal('description'),
                ]);
                $multilang->user()->associate($business->user);
                $multilang->save();
            }
            $this->output->write('.');
        }
        $this->info('Done');
    }
}
