<?php namespace App\Appointment\Models\Trimmer;
use Config, Util;

class SMSTrimmer
{
    /**
     * SMS length mode
     */
    private $mode;

    /**
     * Max length allow
     */
    private $max = 160;

    function __construct($mode) {
        $this->mode = $mode;
    }

    /**
     * Trim the sms content based on admin setting
     *
     * @return string
     */
    public function trim($content)
    {
        if (strtolower($this->mode) === 'off') {
            return $content;
        }

        switch($this->mode)
        {
            case '160':
                $max = 160;
                break;
            case '70':
                $max = 70;
                break;
            default:
                $max = 160;
        }

        return mb_substr($content, 0, $max);
    }
}
