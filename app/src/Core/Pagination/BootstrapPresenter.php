<?php namespace App\Core\Pagination;

class BootstrapPresenter extends \Illuminate\Pagination\BootstrapPresenter
{
    /**
     * @{@inheritdoc}
     */
    public function getPageLinkWrapper($url, $page, $rel = null)
    {
        $rel = is_null($rel) ? '' : ' rel="'.$rel.'"';

        return '<li><a href="/'.$url.'"'.$rel.'>'.$page.'</a></li>';
    }
}
