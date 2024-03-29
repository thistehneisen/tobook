<?php namespace App\Haku\Transformers;

use App\Core\Models\Business;
use Paginator;

trait BusinessTransformer
{
    public function transformResults($results)
    {
        $items = [];
        foreach ($results['hits']['hits'] as $item) {
            $business = Business::ofUser($item['_id'])->first();
            if ($business !== null) {
                $items[] = $business;
            }
        }

        return Paginator::make(
            $items,
            $results['hits']['total'],
            array_get($this->params, 'size')
        );
    }
}
