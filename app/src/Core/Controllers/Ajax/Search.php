<?php namespace App\Core\Controllers\Ajax;

use DB;
use App\Core\Models\BusinessCategory;
use App\Core\Models\User as BusinessModel;

class Search extends Base
{
    public function getServices()
    {
        $categories = BusinessCategory::getAll();
        $result = [];
        foreach ($categories as $cat) {
            $result[] = $cat->name;
            if ($cat->keywords !== '') {
                $result = array_merge($result, array_map('trim', explode(',', $cat->keywords)));
            }
            foreach ($cat->children as $subCat) {
                $result[] = $subCat->name;
                if ($subCat->keywords !== '') {
                    $result = array_merge($result, array_map('trim', explode(',', $subCat->keywords)));
                }
            }
        }
        return $this->json($result, 200);
    }

    public function getLocations()
    {
        $locations = DB::table('users')->select('city AS name')->where('city', '!=', '')->get();
        return $this->json($locations, 200);
    }

    public function showBusiness($businessId)
    {
        $business = BusinessModel::find($businessId);
        return $this->view('front.search._business', [
            'business' => $business
        ]);
    }
}
