<?php namespace App\Search\Providers;

use App\Search\ProviderInterface;
use Elasticsearch\Client;

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
        $this->client->index($params);
    }
}
