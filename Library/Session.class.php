<?php

/**
 * Description of Session
 *
 * @author danpit134
 */
class Session {
    function __construct() {
        session_start();
    }
    
    function set($name, $value) {
        $id = md5(rand(1, 999999999));
        $_SESSION[$name]["value"] = $value;
        $_SESSION[$name][] = $id;
        
        $fileName = "ses-". $id .".session";
        $fileName = ROOT.DS.'Tmp'.DS.'Sessions'.DS.$fileName;
        $variable = $id . DS . $value;
        $handle = fopen($fileName, 'a');
        fwrite($handle, serialize($variable));
        fclose($handle);
        
        return $this;
    }
    
    function get($name) {
        return $_SESSION[$name]["value"];
    }
    
    function sessionDestroy($name = NULL) {
        if ($name != NULL) {
            unset($_SESSION[$name]["value"]);
            unset($_SESSION[$name]);
        } else {
            session_destroy();
        }
    }
    
    function isActive($name) {
        if (isset($_SESSION[$name])) {
            return true;
        }
        return false;
    }
}

