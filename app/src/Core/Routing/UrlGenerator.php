<?php namespace App\Core\Routing;

use Config;

class UrlGenerator extends \Illuminate\Routing\UrlGenerator
{
    /**
     * {@inheritdoc}
     */
    public function route($name, $parameters = array(), $absolute = true, $route = null)
    {
        $result = parent::route($name, $parameters, $absolute, $route);
        $url = parse_url($result);
        if (!isset($url['path'])) {
            $url['path'] = '';
        }

        // Attach current system locale
        $url['path'] = Config::get('app.locale').$url['path'];

        $final = $url['scheme'].'://'.$url['host'].'/'.$url['path'];
        return !empty($url['query'])
            ? $final.'?'.$url['query']
            : $final;
    }
}
