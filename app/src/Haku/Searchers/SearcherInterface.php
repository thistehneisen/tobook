<?php namespace App\Haku\Searchers;

interface SearcherInterface
{
    public function getIndexName();
    public function getType();
    public function getQuery();
    public function getSort();
    public function getPagination($from = 0, $size = 15);
    public function search();
    public function transformResults($raw);
}
