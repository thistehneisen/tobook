<?php namespace App\Search\Providers;

use App\Search\ProviderInterface;
use Elasticsearch\Client;
use Log, Es;

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

    /**
     * @{@inheritdoc}
     */
    public function search($params)
    {
        return Es::search($params);
    }

    /**
     * @{@inheritdoc}
     */
    public function delete($params)
    {
        Log::info('Delting an index');
        try {
            $this->client->indicies()->delete($params);
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
        }
    }
}
