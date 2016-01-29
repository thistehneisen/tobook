<?php namespace App\Core\Pagination;

class PaginationServiceProvider extends \Illuminate\Pagination\PaginationServiceProvider
{
    /**
     * @{@inheritdoc}
     */
    public function register()
    {
        $this->app->bindShared('paginator', function($app)
        {
            $paginator = new Factory($app['request'], $app['view'], $app['translator']);
            $paginator->setViewName($app['config']['view.pagination']);

            // Support i18n URLs
            $paginator->setBaseUrl($app->getLocale().'/'.$app['request']->path());
            if (is_tobook()){
                $paginator->setBaseUrl('tobook'.$app->getLocale().'/'.$app['request']->path());
            }
            $app->refresh('request', $paginator, 'setRequest');

            return $paginator;
        });
    }
}
