<?php
if (!defined("ROOT_PATH"))
{
    header("HTTP/1.1 403 Forbidden");
    exit;
}
require_once ROOT_PATH . 'core/framework/components/pjToolkit.component.php';

class pjUtil extends pjToolkit
{
    static public function uuid()
    {
        return chr(rand(65,90)) . chr(rand(65,90)) . time();
    }

    static public function getReferer()
    {
        if (isset($_GET['_escaped_fragment_']))
        {
            if (isset($_SERVER['REDIRECT_URL']))
            {
                return $_SERVER['REDIRECT_URL'];
            }
        }

        if (isset($_SERVER['HTTP_REFERER']))
        {
            $pos = strpos($_SERVER['HTTP_REFERER'], "#");
            if ($pos !== FALSE)
            {
                // IE fix
                return substr($_SERVER['HTTP_REFERER'], 0, $pos);
            }
            return $_SERVER['HTTP_REFERER'];
        }
    }

    static public function getTimezone($offset)
    {
        $db = array(
            '-14400' => 'America/Porto_Acre',
            '-18000' => 'America/Porto_Acre',
            '-7200' => 'America/Goose_Bay',
            '-10800' => 'America/Halifax',
            '14400' => 'Asia/Baghdad',
            '-32400' => 'America/Anchorage',
            '-36000' => 'America/Anchorage',
            '-28800' => 'America/Anchorage',
            '21600' => 'Asia/Aqtobe',
            '18000' => 'Asia/Aqtobe',
            '25200' => 'Asia/Almaty',
            '10800' => 'Asia/Yerevan',
            '43200' => 'Asia/Anadyr',
            '46800' => 'Asia/Anadyr',
            '39600' => 'Asia/Anadyr',
            '0' => 'Atlantic/Azores',
            '-3600' => 'Atlantic/Azores',
            '7200' => 'Europe/London',
            '28800' => 'Asia/Brunei',
            '3600' => 'Europe/London',
            '-39600' => 'America/Adak',
            '32400' => 'Asia/Shanghai',
            '36000' => 'Asia/Choibalsan',
            '-21600' => 'America/Chicago',
            '-25200' => 'Chile/EasterIsland',
            '-43200' => 'Pacific/Kwajalein'
        );
        if (is_null($offset) && strlen($offset) === 0)
        {
            return $db;
        }
        return array_key_exists($offset, $db) ? $db[$offset] : false;
    }

    public static function printNotice($title, $body, $convert = true, $close = true)
    {
        ?>
        <div class="notice-box">
            <span class="notice-info">&nbsp;</span>
            <?php
            if (!empty($title))
            {
                printf('<span class="block bold">%s</span>', $convert ? htmlspecialchars(stripslashes($title)) : stripslashes($title));
            }
            if (!empty($body))
            {
                printf('<span class="block">%s</span>', $convert ? htmlspecialchars(stripslashes($body)) : stripslashes($body));
            }
            if ($close)
            {
                ?><a href="#" class="notice-close"></a><?php
            }
            ?>
        </div>
        <?php
    }

    static public function textToHtml($content)
    {
        $content = preg_replace('/\r\n|\n/', '<br />', $content);
        return '<html><head><title></title></head><body>'.$content.'</body></html>';
    }
}

function __($key, $return=false, $escape=false) {
    global $i18n;

    if (isset($i18n[$key]) && !empty($i18n[$key])) {
        $text = $i18n[$key];
    } else {
        $text = $key;
    }

    if ($return) {
        return !$escape ? $text : (!is_array($text) ? htmlspecialchars($text) : array_map('htmlspecialchars', $text));
    }
    echo !$escape ? $text : htmlspecialchars($text);
}

function __autoload($className)
{
    $paths = array(
        PJ_FRAMEWORK_PATH . $className . '.class.php',
        PJ_CONTROLLERS_PATH . $className . '.controller.php',
        PJ_MODELS_PATH . str_replace('Model', '', $className) . '.model.php',
        PJ_COMPONENTS_PATH. $className . '.component.php',
        PJ_FRAMEWORK_PATH . 'components/'. $className . '.component.php'
    );

    foreach ($paths as $filename)
    {
        if (is_file($filename))
        {
            require $filename;
            return;
        }
    }
}
?>
