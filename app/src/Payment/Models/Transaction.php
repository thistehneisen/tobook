<?php namespace App\Payment\Models;

class Transaction extends \AppModel
{
    public $fillable = [
        'amount',
        'currency',
        'paygate',
        'status',
        'message',
        'code',
        'reference',
    ];

    public $rulesets = [
        'saving' => [
            'amount' => 'required|numeric'
        ]
    ];

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function cart()
    {
        return $this->belongsTo('App\Core\Models\Cart');
    }

    //--------------------------------------------------------------------------
    // ATTRIBUTES
    //--------------------------------------------------------------------------
    /**
     * Get a symbol for this currency
     *
     * @return string
     */
    public function getCurrencySymbolAttribute()
    {
        $map = [
            'EUR' => '&euro;',
            'GBP' => '&pound;',
            'USD' => '$',
        ];

        $key = $this->currency;

        return isset($map[$key]) ? $map[$key] : $key;
    }

    /**
     * If a currency is not presented, set default value to EUR
     *
     * @return string
     */
    public function getCurrencyAttribute()
    {
        return !empty($this->attributes['currency'])
            ? $this->attributes['currency']
            : 'EUR';
    }

    /**
     * Format amount with currency symbol
     *
     * @return string
     */
    public function getFormattedAmountAttribute()
    {
        return $this->amount.$this->currency_symbol;
    }

    /**
     * Always set 2-decimal place for amount value
     *
     * @param double $value
     */
    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = number_format(round($value, 2), 2);
    }

}
