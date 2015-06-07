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
        $categories       = [];
        $masterCategories = [];
        $keywords         = [];

        $document = $this->getDocument();

        foreach ($document->businessCategories as $item) {
            $categories[] = $item->nice_original_name;
            $keywords = array_merge($keywords, $item->keywords);
        }

        foreach ($document->user->asServices as $asService) {
            if (!empty($asService->masterCategory->id)) {
                $masterCategories[] = $asService->masterCategory->getAllMultilingualAttributes();
            }

            if (!empty($asService->treatmentType->id)) {
                $masterCategories[] = $asService->treatmentType->getAllMultilingualAttributes();
            }
        }

        return [
            // Filter exists only works with null value, so let it be null
            'name'              => $document->name,
            'categories'        => $categories,
            'master_categories' => $masterCategories,
            'keywords'          => $keywords,
            'address'           => $document->address ?: '',
            'district'          => $document->district ?: 'Oulu',
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

    public function getMapping()
    {
        return [
            'name'              => ['type' => 'string'],
            'categories'        => ['type' => 'string', 'index_name' => 'category'],
            'master_categories' => ['type' => 'string', 'index_name' => 'master_category'],
            'keywords'          => ['type' => 'string', 'index_name' => 'keyword'],
            'address'           => ['type' => 'string'],
            'district'          => ['type' => 'string'],
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
}
