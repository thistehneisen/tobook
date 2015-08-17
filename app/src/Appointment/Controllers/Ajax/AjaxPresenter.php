<?php namespace App\Appointment\Controllers\Ajax;

class AjaxPresenter extends \Illuminate\Pagination\BootstrapPresenter
{
    protected $class = 'ajaxPaginatorLink';
    /**
     * Get HTML wrapper for a page link.
     *
     * @param  string $url
     * @param  int    $page
     * @param  string $rel
     * @return string
     */
    public function getPageLinkWrapper($url, $page, $rel = null)
    {
        $rel = is_null($rel) ? '' : ' rel="'.$rel.'"';

        return '<li><a class="' . $this->class .'" '. $this->getDataAttributes($url). ' data-page="' . $page. '" href="/'.$url.'"'.$rel.'>'.$page.'</a></li>';
    }

    /**
     * Parse url params and transform to data-* attributes
     * @param  type $url
     * @return type
     */
    public function getDataAttributes($url)
    {
        $query = parse_url($url, PHP_URL_QUERY);
        $params = explode("&", $query);
        $data = '';
        foreach ($params as $param) {
            $parts = explode("=", $param);
            $data .= sprintf('data-%s="%s" ', $parts[0], $parts[1]);
        }

        return $data;
    }
}
