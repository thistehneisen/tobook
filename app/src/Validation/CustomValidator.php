<?php

namespace App\Validation;

class CustomValidator extends \Illuminate\Validation\Validator
{
    private $_custom_messages = array(
      'gms0338' => 'The :attribute can only contain GMS 0338 characters',
    );

    public function __construct($translator, $data, $rules, $messages = array(), $customAttributes = array())
    {
        parent::__construct($translator, $data, $rules, $messages, $customAttributes);
        $this->_set_custom_stuff();
    }

    /**
     * Setup any customizations etc.
     */
    protected function _set_custom_stuff()
    {
      //setup our custom error messages
      $this->setCustomMessages($this->_custom_messages);
    }

    /***
     * Regex string for testing GSM 7 03.38 characters in a very
     * compact way without the need for escaping special characters
     * by performing unicode range comparison. Characters mostly ordered
     * as per this table http://en.wikipedia.org/wiki/GSM_03.38
     *
     * TODO Not entirely sure what to do with SS1 Single Shift Escape
     * @see http://michaelsanford.com/php-regex-for-gsm-7-03-38/
     * @see https://github.com/varaa/varaa/issues/616
     */
      public function validateGms0338($attribute, $value)
      {
        return (preg_match('/^[\x{20}-\x{7E}£¥èéùìòÇ\rØø\nÅåΔ_ΦΓΛΩΠΨΣΘΞ\x{1B}ÆæßÉ ¤¡ÄÖÑÜ§¿äöñüà\x{0C}€]*$/u', $value) === 1);
      }
}
