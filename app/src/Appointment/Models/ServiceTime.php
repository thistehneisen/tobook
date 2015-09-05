<?php namespace App\Appointment\Models;

use Settings;
use Config;
use App\Core\Models\Multilanguage;

class ServiceTime extends \App\Core\Models\Base
{
    protected $table = 'as_service_times';

    public $fillable = ['price', 'length','before','during', 'after', 'description'];

    use \App\Appointment\Models\Discount\DiscountPrice;

    /**
     * @see \App\Core\Models\Base
     */
    public $multilingualAtrributes = ['description'];

    public function setLength()
    {
        $this->length = (int) $this->after + $this->during + $this->before;
    }

    /**
     * Get current context for retreive correct translation in multilanguage table
     *
     * @return string
     */
    public static function getContext()
    {
        return "as_service_times_";
    }

    /**
     * Quick and dirty, improve later
     */
    public function saveMultilanguage($descriptions)
    {
        Multilanguage::saveValues($this->id, static::getContext(), 'description', $descriptions);
    }

    /**
     * {@inheritdoc}
     */
    public function fill(array $attributes)
    {
        $defaultLanguage = Config::get('varaa.default_language');

        $data = $attributes;

        foreach ($this->multilingualAtrributes as $key) {
            $data[$key] = (!empty($data[$key.'s'][ $defaultLanguage]))
            ? $data[$key.'s'][ $defaultLanguage] : '';
        }

        return parent::fill($data);
    }

    //--------------------------------------------------------------------------
    // ATTRIBUTES
    //--------------------------------------------------------------------------

    public function getFormattedPriceAttribute()
    {
        return show_money($this->attributes['price']);
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function service()
    {
        return $this->belongsTo('App\Appointment\Models\Service');
    }
}
