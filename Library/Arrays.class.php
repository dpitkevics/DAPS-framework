<?php

/**
 * Description of Array
 *
 * @author danpit134
 */
class Arrays {
    function upperFirst($array) {
        $arr = array();
        if (!is_array(is_array($array))) {
            foreach ($array as $data) {
                $arr[] = ucfirst($data);
            }
            return $arr;
        } else {
            foreach ($array as $sarray) {
                $this->upperFirst($sarray);
            }
        }
    }
}
