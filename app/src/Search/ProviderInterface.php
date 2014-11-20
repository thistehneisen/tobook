<?php namespace App\Search;

interface ProviderInterface
{
    /**
     * Send data to the service to be indexed
     *
     * @param array $params
     *
     * @return void
     */
    public function index($params);
}
