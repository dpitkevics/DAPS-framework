<?php

/**
 * Description of FormModel
 *
 * @author danpit134
 */
class FormModel extends SQLQuery{
    protected $_model;

    function __construct() {
        global $inflect;
        global $url;
        $urlArray = explode ('/', $url);
        
        $this->connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
        $this->_model = $urlArray[0];  
        $this->_table = strtolower($inflect->pluralize($this->_model));   
        if (!isset($this->abstract)) {
            $this->_describe();
        }
    }

    function __destruct() {
    }
}
