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

    /**
     * Perform searching on given params
     *
     * @param array $params
     *
     * @return array
     */
    public function search($params);
}
