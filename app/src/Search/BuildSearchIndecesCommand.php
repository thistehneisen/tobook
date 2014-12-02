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
                'type' => 'custom',
                'tokenizer' => 'varaa_tokenizer',
                'filter' => ['lowercase']
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

        // Auto mapping all fillable fields of that model to use
        $map = ['_source' => ['enabled' => true]];

        // Get all fillable fields
        $instance = new $model();
        foreach ($instance->getFillable() as $field) {
            $map['properties'][$field] = [
                'type' => 'string',
                'analyzer' => 'varaa_ngrams'
            ];
        }
        $params['body']['mappings'][$model::getSearchIndexType()] = $map;

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
