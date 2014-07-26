<?php

/**
 * Encoder Behavior
 *
 * PHP version 5
 *
 * @category Behavior
 * @package  Croogo
 * @version  1.0
 * @author   Fahad Ibnay Heylaal <contact@fahad19.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.croogo.org
 */
class EncoderBehavior extends ModelBehavior {

    public function setup(&$model, $config = array()) {
        if (is_string($config)) {
            $config = array($config);
        }

        $this->settings[$model->alias] = $config;
    }

    /**
     * Encode data
     *
     * Turn array into a JSON
     *
     * @param object $model model
     * @param array $data data
     * @param array $options (optional)
     * @return string
     */
    public function encodeData(&$model, $data, $options = array()) {


        return myserialize($data);
    }

    /**
     * Decode data
     *
     * @param object $model model
     * @param string $data data
     * @return array
     */
    public function decodeData(&$model, $data) {
        if (empty ($data)) {
            return array();
        }
        try {
            $out = myunserialize($data);
        } catch (Exception $e) {
            $out = array();
        }

        return $out;
    }

}

?>
