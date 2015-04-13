<?php namespace App\Core\Commands;

use App\Core\Models\Business;
use App\Core\Models\Multilanguage;
use Config;
use Illuminate\Console\Command;

class MoveMetaCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'varaa:move-meta';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Move meta data of businesses to target language';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $defaultLanguage = Config::get('varaa.default_language');
        $businesses = Business::with('user')
            ->where('meta_title', '!=', '')
            ->orWhere('meta_description', '!=', '')
            ->orWhere('meta_keywords', '!=', '')
            ->get();

        foreach ($businesses as $business) {
            foreach (['meta_title', 'meta_description', 'meta_keywords'] as $key) {
                $multilang = Multilanguage::where('context', $business->getTable())
                    ->where('key', $key)
                    ->where('lang', $defaultLanguage)
                    ->where('user_id', $business->user_id)
                    ->first();

                if ($multilang === null) {
                    $multilang = new Multilanguage([
                        'context' => $business->getTable(),
                        'key'     => $key,
                        'lang'    => $defaultLanguage,
                        'value'   => $business->getOriginal($key),
                    ]);
                    $multilang->user()->associate($business->user);
                    $multilang->save();
                    $this->output->write('.');
                }
            }
        }
        $this->info('Done');
    }
}
