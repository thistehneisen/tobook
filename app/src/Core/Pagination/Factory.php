<?php namespace App\Core\Pagination;

use Illuminate\Pagination\Paginator;

class Factory extends \Illuminate\Pagination\Factory
{
    /**
     * @{@inheritdoc}
     */
    public function make(array $items, $total, $perPage = null)
    {
        $paginator = new Paginator($this, $items, $total, $perPage);
        // Get all params in query string and append
        $paginator->appends($this->request->all());

        return $paginator->setupPaginationContext();
    }
}
