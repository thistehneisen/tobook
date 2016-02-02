<?php namespace App\Core\Controllers;

use App;
use Carbon\Carbon;
use Laravelista\Bard\UrlSet as Sitemap;
use App\Appointment\Models\MasterCategory;
use App\Core\Models\Business;

use Route;
class SitemapController extends Base
{
	protected $sitemap;

    public function __construct(Sitemap $sitemap) 
    {
        $this->sitemap = $sitemap;
    }

	public function index()
    {
   		$home = $this->sitemap->addUrl(route('home'));
        // $home->setPriority(0.8);
        // $home->setChangeFrequency('monthly');
        // $home->setLastModification(Carbon::now());
        
		/**
		 * Home page
		 */
		$this->sitemap->addUrl(route('home'));
		$this->sitemap->addUrl(route('contact'));
		$this->sitemap->addUrl(route('policy'));
		$this->sitemap->addUrl(route('about'));
		$this->sitemap->addUrl(route('business'));
		$this->sitemap->addUrl(route('intro'));

		/**
		 * Business categories
		 */
		$categories = MasterCategory::getAll();
		foreach ($categories as $category) {
			$this->sitemap->addUrl(route('business.master_category', [$category->id, $category->slug]));

			foreach ($category->treatments as $treatment) {
				$this->sitemap->addUrl(route('business.treatment', [$treatment->id, $treatment->slug]));
			}
		}

		/**
		 * Business pages
		 */
		$businesses = Business::where('is_hidden', '=', false)->get();

		foreach ($businesses as $business) {
			$this->sitemap->addUrl(route('business.index', [$business->id, $business->slug]));
		}

        return $this->sitemap->render();    
    }
}