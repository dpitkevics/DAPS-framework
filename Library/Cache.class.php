<?php
class Cache {
    function get($fileName) {
        $fileName = ROOT.DS.'Tmp'.DS.'Cache'.DS.$fileName;
        if (file_exists($fileName)) {
            $handle = fopen($fileName, 'rb');
            $variable = fread($handle, filesize($fileName));
            fclose($handle);
            return unserialize($variable);
        } else {
            return null;
        }
    }

    function set($fileName,$variable) {
        $fileName = ROOT.DS.'Tmp'.DS.'Cache'.DS.$fileName;
        $handle = fopen($fileName, 'a');
        fwrite($handle, serialize($variable));
        fclose($handle);
    }
}
