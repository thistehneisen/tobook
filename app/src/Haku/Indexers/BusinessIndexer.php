<?php namespace App\Haku\Indexers;

use App\Core\Models\Business;
use InvalidArgumentException;

class BusinessIndexer extends AbstractIndexer
{
    const INDEX_NAME = 'businesses';
    const INDEX_TYPE = 'business';

    public function getIndexName()
    {
        return self::INDEX_NAME;
    }

    public function getType()
    {
        return self::INDEX_TYPE;
    }

    public function getId()
    {
        return $this->getDocument()->user_id;
    }

    public function getBody()
    {
        $keywords = [];
        $categories = [];
        $masterCategories = [];

        $document = $this->getDocument();

        foreach ($document->businessCategories as $item) {
            $categories[] = $item->nice_original_name;
            $keywords = array_merge($keywords, $item->keywords);
        }

        if (empty($document->user)) {
            return;
        }

        foreach ($document->user->asServices as $asService) {
            if (!empty($asService->masterCategory->id)) {
                $masterCategories['mc_'.$asService->masterCategory->id] = true;
            }

            if (!empty($asService->treatmentType->id)) {
                $masterCategories['tm_'.$asService->treatmentType->id] = true;
            }
        }

        return [
            // Filter exists only works with null value, so let it be null
            'name'              => $document->name,
            'categories'        => $categories,
            'master_categories' => array_keys($masterCategories),
            'keywords'          => $keywords,
            'address'           => $document->address ?: '',
            'district'          => $document->district ?: '',
            'postcode'          => $document->postcode ?: '',
            'city'              => $document->city ?: '',
            'country'           => $document->country ?: '',
            'phone'             => $document->phone ?: '',
            'description'       => $document->description ?: '',
            'location'          => [
                'lat' => $document->lat ?: 0,
                'lon' => $document->lng ?: 0
            ]
        ];
    }

    public static function getMapping()
    {
        return [
            'name'              => ['type' => 'string'],
            'categories'        => ['type' => 'string', 'index_name' => 'category'],
            'master_categories' => ['type' => 'string', 'index_name' => 'master_category', 'analyzer' => 'standard'],
            'keywords'          => ['type' => 'string', 'index_name' => 'keyword'],
            'address'           => ['type' => 'string'],
            'district'          => ['type' => 'string', 'analyzer' => 'standard'],
            'postcode'          => ['type' => 'string'],
            'city'              => ['type' => 'string'],
            'country'           => ['type' => 'string'],
            'phone'             => ['type' => 'string'],
            'description'       => ['type' => 'string'],
            'location'          => ['type' => 'geo_point'],
        ];
    }

    public function setDocument($document)
    {
        if (!($document instanceof Business)) {
            throw new InvalidArgumentException('Data must be an instance of App\Core\Models\Business');
        }

        parent::setDocument($document);
    }

    public function index()
    {
        // If this business is hidden, don't index and remove existing index
        if ($this->getDocument()->is_hidden) {
            $this->delete();

            return;
        }

        // Otherwise, just index as normal
        parent::index();
    }
}
