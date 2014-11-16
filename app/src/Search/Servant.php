<?php namespace App\Search;

use App\Core\Models\Business;

/**
 * Indexing/searching functions to interact with ElasticSearch (atm)
 * @author Hung Nguyen <hung@varaa.com>
 */
class Servant
{
    protected static $instance = null;

    public static function getInstance()
    {
        if(is_null(self::$instance))
        {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Upsert ElasticSearch index with given index, type and params
     *
     * @param string $index
     * @param string $type
     * @param array $params
     * @return json
     */
    public function upsertIndex($index, $type, $params)
    {
        $client = new \Elasticsearch\Client();
        $params['index'] = $index;
        $params['type']  = $type;
        return $client->index($params);
    }

    /**
     * Update ElasticSearch index businesses/business for a business user
     *
     * @param \App\Core\Models\Business $business
     * @return json
     */
    public function upsertIndexForBusiness(Business $business)
    {
        $categoryNameList = [];
        $categoryKeywordList = [];

        foreach ($business->businessCategories as $category) {
            $categoryNameList[]    = str_replace('_', ' ', $category->name);
            $categoryKeywordList[] = implode(', ', $category->keywords);
        }

        $categoryNames    = implode(', ', $categoryNameList);
        $categoryKeywords = implode(', ', $categoryKeywordList);

        $params['id'] = $business->user->id;

        $params['body'] = [
            // 'business_name' => $business->name ?: '',
            'business_name' => $business->name,// filter exists only works with null value, so let it be null
            'category_name' => $categoryNames,
            'keywords'      => $categoryKeywords,
            'address'       => $business->address ?: '',
            'postcode'      => $business->postcode ?: '',
            'city'          => $business->city ?: '',
            'country'       => $business->country ?: '',
            'phone'         => $business->phone ?: '',
            'description'   => $business->description ?: '',
            'location'      => [
                'lat' => $business->lat ?: 0,
                'lon' => $business->lng ?: 0
            ]
        ];

        try{
            $responses = $this->upsertIndex('businesses', 'business', $params);
        } catch(\Exception $ex){
            throw $ex;
        }

        return $responses;
    }
}
