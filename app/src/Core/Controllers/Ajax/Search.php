<?php namespace App\Core\Controllers\Ajax;

use DB;
use App\Core\Models\BusinessCategory;
use App\Core\Models\User;

class Search extends Base
{
    /**
     * Prepare services data for typeahead
     *
     * @return JSON
     */
    public function getServices()
    {
        $categories = BusinessCategory::getAll();
        $result = [];
        foreach ($categories as $cat) {
            $result[] = $cat->name;
            $result = array_merge($result, $cat->keywords);
            foreach ($cat->children as $subCat) {
                $result[] = $subCat->name;
                $result = array_merge($result, $subCat->keywords);
            }
        }

        return $this->json($result);
    }

    /**
     * Prepare location data for typeadhead
     *
     * @return JSON
     */
    public function getLocations()
    {
        $locations = DB::table(with(new User)->getTable())
            ->select('city AS name')
            ->where('city', '!=', '')
            ->distinct()
            ->get();

        return $this->json($locations);
    }

    public function showBusiness($businessId)
    {
        $business = User::find($businessId);
        return $this->view('front.search._business', [
            'business' => $business
        ]);
    }
}
