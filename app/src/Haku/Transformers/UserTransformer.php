<?php namespace App\Haku\Transformers;

use App\Core\Models\User;
use Paginator;

trait UserTransformer
{
    public function transformResults($results)
    {
        $items = [];
        foreach ($results['hits']['hits'] as $item) {
            $user = User::find($item['_id']);
            if ($user !== null) {
                $items[] = $user;
            }
        }

        return Paginator::make(
            $items,
            $results['hits']['total'],
            array_get($this->params, 'size')
        );
    }
}
