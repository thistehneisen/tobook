<?php namespace App\Core\Commands;

use Illuminate\Console\Command;
use App\Search\Commands;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Search\Commands\BuildSearchIndecesCommand;
use App\Appointment\Models\MasterCategory;
use Elasticsearch\Client;
use App\Core\Models\User;
use App\Core\Models\Business;
use DB, Log;
use Queue;

class BuildNewIndexForBusinesses extends Command{

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'varaa:build-new-indices-for-mc-and-businesses';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Build new indices for businesses based on master categories';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
        $this->client = new Client();

        $model = 'App\Core\Models\Business';

        $masterCategories = MasterCategory::all();

        foreach ($masterCategories as $masterCategory) {
            $this->processMasterCategoryBusinessIndex($model, $masterCategory);
            foreach ($masterCategory->treatments as $treatment) {
                $this->processTreatmentTypeBusinessIndex($model, $treatment);
            }
        }
	}

    public function processTreatmentTypeBusinessIndex($model, $treatment)
    {
        $this->info('Enqueue to rebuild index of Busineses belong to (treatment): ' . $treatment->name);
        $this->createIndex($model, $treatment);
        $items = DB::table('businesses')
            ->join('users', 'users.id', '=', 'businesses.user_id')
            ->join('as_services', 'as_services.user_id', '=', 'users.id')
            ->where('as_services.treatment_type_id', '=', $treatment->id)
            ->select('businesses.id')
            ->distinct()
            ->get();
        $this->updateIndex($items, $model, $treatment->getParentSearchIndexName());
    }

    public function processMasterCategoryBusinessIndex($model, $masterCategory)
    {
        $this->info('Enqueue to rebuild index of Busineses belong to (mc): ' . $masterCategory->name);
        $this->createIndex($model, $masterCategory);
        $items = DB::table('businesses')
            ->join('users', 'users.id', '=', 'businesses.user_id')
            ->join('as_services', 'as_services.user_id', '=', 'users.id')
            ->where('as_services.master_category_id', '=', $masterCategory->id)
            ->select('businesses.id')
            ->distinct()
            ->get();
        $this->updateIndex($items, $model, $masterCategory->getParentSearchIndexName());
    }

    public function updateIndex($items, $model, $searchIndexName)
    {
        foreach ($items as $item) {
            // Push into queue to reindex
            $id = $item->id;
            Queue::push(function ($job) use ($model, $id, $searchIndexName) {
                $item = $model::find($id);
                if ($item) {
                    Log::info('Model: '. $model);
                    Log::info('Search Index Name:' . $searchIndexName);
                    $item->updateSearchIndex($searchIndexName);
                    $job->delete();
                }
            });
        }
    }

    /**
     * Create index for a model, if not existing
     *
     * @param string $model Model name
     *
     * @return void
     */
    protected function createIndex($model, $parentModel)
    {
        $instance = new $model();

        $params = [];
        $params['index'] = $parentModel->getParentSearchIndexName();
        // If it exists already, return ASAP
        if ($this->client->indices()->exists($params)) {
            return;
        }

        // Build a custom tokenizer
        $params['body']['settings']['analysis']['analyzer'] = [
            'varaa_ngrams' => [
                'type'        => 'custom',
                'tokenizer'   => 'varaa_tokenizer',
                'char_filter' => ['html_strip'],
                'filter'      => ['lowercase'],
            ]
        ];

        //Index 'Hakunamatata' => 'Hak','aku','una' .etc
        $params['body']['settings']['analysis']['tokenizer'] = [
            'varaa_tokenizer' => [
                'type'        => 'nGram',
                'min_gram'    => '4',
                'max_gram'    => '8',
                'token_chars' => ['letter', 'digit']
            ]
        ];

        // Get search map of this model
        $properties = $instance->getSearchMapping();

        // Go to each field and check if there's a analyzer set.
        // If not, use our custom analyzer
        foreach ($properties as &$field) {
            if (!isset($field['analyzer']) && $field['type'] === 'string') {
                $field['analyzer'] = 'varaa_ngrams';
            }
        }

        $params['body']['mappings'][str_singular($parentModel->getParentSearchIndexName())] = [
            '_source' => [
                'enabled' => true
            ],
            'properties' => $properties
        ];

        $this->client->indices()->create($params);
    }

}
