<?php namespace App\Search;

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
     * @param \App\Core\Models\User $business
     * @return json
     */
    public function upsertIndexForBusiness($business)
    {

        $params['id'] = $business->id;

        $businessName =   (!empty($business->business_name))
                ?  $business->business_name
                : null;

        $categoryNameList = [];
        $categoryKeywordList = [];

        foreach ($business->businessCategories as $category) {
            $categoryNameList[]    = str_replace('_', ' ', $category->name);
            $categoryKeywordList[] = implode(", " , $category->keywords);
        }

        $categoryNames    = implode(", ", $categoryNameList);
        $categoryKeywords = implode(", ", $categoryKeywordList);

        $params['body'] = [
            'name'          => $business->username,
            'business_name' => $businessName,
            'category_name' => $categoryNames,
            'keywords'      => $categoryKeywords,
            'address'       => $business->address,
            'postcode'      => $business->postcode,
            'city'          => $business->city,
            'country'       => $business->country,
            'phone'         => $business->phone,
            'description'   => $business->description,
            'location'      => [
                'lat'  => empty($business->lat) ? 0 : $business->lat,
                'lon'  => empty($business->lng) ? 0 : $business->lng
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
