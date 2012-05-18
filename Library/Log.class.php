<?php
class Log {
    function set($variable) {
        $fileName = "log-". date("YmdHis") .".log";
        $fileName = ROOT.DS.'Tmp'.DS.'Logs'.DS.$fileName;
        $variable = $_SERVER['REMOTE_ADDR'] . DS . date("Y-m-d_H:i:s") . DS . $variable;
        $handle = fopen($fileName, 'a');
        fwrite($handle, serialize($variable));
        fclose($handle);
    }
}
