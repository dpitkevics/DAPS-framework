<?php

/*
 * Checking for development environment and displaying errors
 */
function setReporting() {
    if (DEVELOPMENT_ENVIRONMENT == TRUE) {
        error_reporting(E_ALL);
        ini_set('display_errors', 'On');
    } else {
        error_reporting(E_ALL);
        ini_set('display_errors', 'Off');
        ini_set('log_errors', 'On');
        ini_set('error_log', ROOT.DS.'Tmp'.DS.'Logs'.DS.'error.log');
    }
}

/*
 * Checking for Magic Quotes and removing them
 */
function stripSlashesDeep($string) {
    $string = is_array($string) ? array_map('stripSlashesDeep', $string) : stripslashes($string);
    return $string;
}

function removeMagicQuotes() {
    if (get_magic_quotes_gpc()) {
        $_GET    = stripSlashesDeep($_GET);
        $_POST   = stripSlashesDeep($_POST);
        $_COOKIE = stripSlashesDeep($_COOKIE);
    }
}

/*
 * Secondary Call function
 */
function performAction($controller,$action,$queryString = null,$render = 0) {	
    $controllerName = ucfirst($controller).'Controller';
    $dispatch = new $controllerName($controller,$action);
    $dispatch->render = $render;
    return call_user_func_array(array($dispatch,$action),$queryString);
}

/*
 * Routing
 */
function routeURL($url) {
    global $routing;

    foreach ($routing as $pattern => $result) {
        if (preg_match( $pattern, $url )) {
            return preg_replace($pattern, $result, $url);
        }
    }
    return ($url);
}

/*
 * Check register globals and remove them
 */
function unregisterGlobals() {
    if (ini_get('register_globals')) {
        $array = array('_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
        foreach ($array as $value) {
            foreach ($GLOBALS[$value] as $key => $var) {
                if ($var === $GLOBALS[$key]) {
                    unset($GLOBALS[$key]);
                }
            }
        }
    }
}

/*
 * Main function call
 */
function callHook() {
    global $url;
    global $default;
    
    $queryStringArray = array();
    
    if(!isset($url)) {
        $controller = $default["controller"];
        $action = $default["action"];
    } else {
        $url = routeURL($url);
        $urlArray = array();
        $urlArray = explode("/", $url);
        $controller = $urlArray[0];
        array_shift($urlArray);
        if(isset($urlArray[0])) {
            $action = $urlArray[0];
            array_shift($urlArray);
        } else {
            $action = $default["action"];
        }
        
        foreach ($urlArray as $data) {
            $queryStringArray[] = $data;
        }
    }

    $controllerName = $controller;
    $controller = ucwords($controller);
    $model = rtrim($controller, 's');
    $controller .= 'Controller';
    $dispatch = new $controller($controllerName, $action);

    if ((int)method_exists($controller, $action)) {
        call_user_func_array(array($dispatch, $action), $queryStringArray);
    } else {
        die(getError(1));
    }
}

/*
 * Get Error message
 */
function getError($errorNumber, $err = NULL) {
    global $ERRORS;
    global $log;
    
    $error = 'ERROR_'. $errorNumber;
    
    /*
     * Logging error
     */
    $log->set(($err!=NULL)?$ERRORS[$error]. DS .$err:$ERRORS[$error]);
    return $ERRORS[$error];
}

/*
 * Autoload any required classes
 */
function __autoload($className) {
    if (file_exists(ROOT . DS . 'Library' . DS . $className . '.class.php')) {
        require_once(ROOT . DS . 'Library' . DS . $className . '.class.php');
    } elseif (file_exists(ROOT . DS . 'API' . DS . 'Controller' . DS . $className . '.php')) {
        require_once(ROOT . DS . 'API' . DS . 'Controller' . DS . $className . '.php');
    } elseif (file_exists(ROOT . DS . 'API' . DS . 'Model' . DS . $className . '.php')) {
        require_once(ROOT . DS . 'API' . DS . 'Model' . DS . $className . '.php');
    } else {
        die(getError(3, $className));
    }
}

/*
 * Calling functions
 */
$cache = new Cache();
$log = new Log();
$inflect = new Inflection();

setReporting();
removeMagicQuotes();
unregisterGlobals();
callHook();