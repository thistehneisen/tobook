<?php namespace App\Search\Providers;

use App\Search\ProviderInterface;
use Elasticsearch\Client;
use Log;

class ElasticSearch implements ProviderInterface
{
    /**
     * Connection to interact with ElasticSearch
     *
     * @var ElasticSearch\Client
     */
    protected $client;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * @{@inheritdoc}
     */
    public function index($params)
    {
        Log::info('Indexing model', $params);
        try {
            $this->client->index($params);
        } catch (\Exception $ex) {
            // Silently failed. Life sucks
            Log::error($ex->getMessage());
        }
    }
}
