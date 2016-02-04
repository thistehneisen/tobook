
<?php namespace App\Core\Routing;

use Config;

class UrlGenerator extends \Illuminate\Routing\UrlGenerator
{
    /**
     * {@inheritdoc}
     */
    public function route($name, $parameters = array(), $absolute = true, $route = null, $locale = null)
    {
        $result = parent::route($name, $parameters, $absolute, $route);

        if (\App::environment() == 'testing') {
            // do not append locale for testing env
            return $result;
        }
        $url = parse_url($result);

        if (!isset($url['path'])) {
            $url['path'] = '';
        }

        if (is_null($locale)) {
            // Attach current system locale
            $url['path'] = Config::get('app.locale').$url['path'];
        } else {
            // or attach the given locale
            $languages = Config::get('varaa.languages');
            if (!in_array($locale, $languages)) {
                throw new \InvalidArgumentException('Upsupported locale: '.$locale);
            }
            $url['path'] = $locale.$url['path'];
        }

        $final = $url['path'];
        if (isset($url['host'])) {
            $final = $url['host'] . '/' . $final;
        }
        if (isset($url['scheme'])) {
            $final = $url['scheme'] . '://' . $final;
        }
        if (isset($url['query'])) {
            $final = rtrim($final, '/') . '/?' . $url['query'];
        }

        return $final;
    }

     /**
     * {@inheritdoc}
     */
    public function asset($path, $secure = null)
    {
        if ($this->isValidUrl($path)) return $path;

        // Once we get the root URL, we will check to see if it contains an index.php
        // file in the paths. If it does, we will remove it since it is not needed
        // for asset paths, but only for routes to endpoints in the application.
        $root = $this->getRootUrl($this->getScheme($secure));

        return $this->removeIndex($root).'/'.trim($path, '/');
    }
}
