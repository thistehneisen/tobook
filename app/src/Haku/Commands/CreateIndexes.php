<?php namespace App\Haku\Commands;

use App;
use App\Core\Models\Business;
use App\Core\Models\User;
use App\Haku\Indexers\BusinessIndexer;
use App\Haku\Indexers\UserIndexer;
use Illuminate\Console\Command;

class CreateIndexes extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'varaa:haku-create-indexes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create indexes for businesses in ES';

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
        $this->client = App::make('elasticsearch');

        $indexers = [
            'App\Haku\Indexers\BusinessIndexer',
            'App\Haku\Indexers\UserIndexer',
        ];

        foreach ($indexers as $indexer) {
            // If there is already an index, skip it
            if ($this->client->indices()->exists(['index' => $indexer::INDEX_NAME])) {
                continue;
            }

            // Create index
            $params = [
                'index' => $indexer::INDEX_NAME,
                'body' => [
                    'settings' => [
                        'analysis' => [
                            'analyzer' => $this->getAnalyzer(),
                            'tokenizer' => $this->getTokenizer(),
                        ],
                    ],
                    'mappings' => [
                        $indexer::INDEX_TYPE => $this->getMapping($indexer::getMapping())
                    ],
                ],
            ];

            $this->client->indices()->create($params);
            $this->info('Index ['.$indexer::INDEX_NAME.'] created.');
        }

        $this->line('Indexing business data');
        $businesses = Business::notHidden()->get();
        foreach ($businesses as $business) {
            $indexer = new BusinessIndexer($business);
            $indexer->index();

            $this->output->write('.');
        }

        $this->line('');
        $this->line('Indexing user data');
        // Leave consumer accounts alone
        $users = User::whereNull('consumer_id')->get();
        foreach ($users as $user) {
            $indexer = new UserIndexer($user);
            $indexer->index();

            $this->output->write('.');
        }

        $this->line('');
        $this->info('Done!');
    }

    protected function getAnalyzer()
    {
        return [
            'varaa_ngrams' => [
                'type'        => 'custom',
                'tokenizer'   => 'varaa_tokenizer',
                'char_filter' => ['html_strip'],
                'filter'      => ['lowercase'],
            ]
        ];
    }

    protected function getTokenizer()
    {
        return [
            'varaa_tokenizer' => [
                'type'        => 'nGram',
                'min_gram'    => '4',
                'max_gram'    => '8',
                'token_chars' => ['letter', 'digit']
            ]
        ];
    }

    protected function getMapping($map)
    {
        $properties = array_map(function ($attr) {
            if (!isset($attr['analyzer']) && $attr['type'] === 'string') {
                $attr['analyzer'] = 'varaa_ngrams';
            }

            return $attr;
        }, $map);

        return [
            '_source' => [
                'enabled' => true
            ],
            'properties' => $properties
        ];
    }
}
