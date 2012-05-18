<?php

/**
 * Description of Cookies
 *
 * @author danpit134
 */
class Cookies {
    function set($name, $value = NULL, $expire = NULL) {
         if ($expire != NULL) {
             $resTime = NULL;             
             $resString = explode(" ", $expire);    // gets day, month, year, second, minute or hour
             $resTime = $resString[0];
             $resString = $resString[1];
             switch ($resString) {
                 case "year":
                     $year = time()+(60*60*24*365);
                     $time = $year * $resTime;
                     break;
                 case "month":
                     $month = time()+(60*60*24*30);
                     $time = $month * $resTime;
                     break;
                 case "week":
                     $week = time()+(60*60*24*7);
                     $time = $week * $resTime;
                     break;
                 case "day":
                     $day = time()+(60*60*24);
                     $time = $day * $resTime;
                     break;
                 case "hour":
                     $hour = time()+(60*60);
                     $time = $hour * $resTime;
                     break;
                 case "minute":
                     $minute = time()+(60);
                     $time = $minute * $resTime;
                     break;
                 case "second":
                     $second = time()+1;
                     $time = $minute * $resTime;
                     break;
                 default:
                     $time = time();
             }
         }
        
        setcookie($name, $value, $time);
        
        return $this;
    }
    
    function get($name) {
        $value = NULL;
        if (isset($_COOKIE[$name]))
            $value = $_COOKIE[$name];
        
        return $value;
    }
    
    function cookieDestroy($name) {
        if (isset($_COOKIE[$name]))
            unset($_COOKIE[$name]);
    }
    
    function isActive($name) {
        if (isset($_COOKIE[$name]))
            return true;
        else
            return false;
    }
}

