<?php namespace App\Search;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Queue;
use Elasticsearch\Client;

class BuildSearchIndecesCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'varaa:build-search-indices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build indices of one or many models';

    /**
     * ElasticSearch client
     *
     * @var ElasticSearch\Client
     */
    protected $client;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $this->client = new Client();

        $default = [
            'App\Core\Models\User',
            'App\Core\Models\Business',
            'App\FlashDeal\Models\Service',
            'App\FlashDeal\Models\FlashDeal',
        ];

        $models = $this->argument('models');
        $models = (!empty($models))
            ? explode(',', $models)
            : $default;

        foreach ($models as $model) {
            $this->info('Enqueue to rebuild index of '.$model);
            $this->createIndex($model);

            foreach ($model::all() as $item) {
                // Push into queue to reindex
                $id = $item->id;
                Queue::push(function ($job) use ($model, $id) {
                    $item = $model::find($id);
                    if ($item) {
                        $item->updateSearchIndex();
                        $job->delete();
                    }
                });
            }
        }
    }

    /**
     * Create index for a model, if not existing
     *
     * @param string $model Model name
     *
     * @return void
     */
    protected function createIndex($model)
    {
        $params = [];
        $params['index'] = $model::getSearchIndexName();
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
        $instance = new $model();
        $properties = $instance->getSearchMapping();

        // Go to each field and check if there's a analyzer set.
        // If not, use our custom analyzer
        foreach ($properties as &$field) {
            if (!isset($field['analyzer']) && $field['type'] === 'string') {
                $field['analyzer'] = 'varaa_ngrams';
            }
        }

        $params['body']['mappings'][$model::getSearchIndexType()] = [
            '_source' => [
                'enabled' => true
            ],
            'properties' => $properties
        ];

        $this->client->indices()->create($params);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['models', InputArgument::OPTIONAL, 'Comma-separated models'],
        ];
    }
}
