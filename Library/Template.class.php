<?php

class Template {
    protected $variables = array();
    protected $_controller;
    protected $_action;
    
    function __construct($controller, $action) {
        $this->_controller = $controller;
        $this->_action = $action;
    }
    
    /*
     * Setting variables
     */
    function set($name, $value) {
        $this->variables[$name] = $value;
    }
    
    /*
     * Displaying Template
     */
    function render($doNotRenderHeader = 0) {
        $html = new HTML();
        extract ($this->variables);

        if($doNotRenderHeader == 0) {
            if (file_exists(ROOT . DS . 'API' . DS . 'View' . DS . strtolower($this->_controller) . DS . 'header.php')) {
                include (ROOT . DS . 'API' . DS . 'View' . DS . strtolower($this->_controller) . DS . 'header.php');
            } else {
                include (ROOT . DS . 'API' . DS . 'View' . DS . 'header.php');
            }
        }
        
        if (file_exists(ROOT . DS . 'API' . DS . 'View' . DS . strtolower($this->_controller) . DS . $this->_action . '.php')) 
            include (ROOT . DS . 'API' . DS . 'View' . DS . strtolower($this->_controller) . DS . $this->_action . '.php');   
        else {
            die (getError(2));
        }

        if($doNotRenderHeader == 0) {
            if (file_exists(ROOT . DS . 'API' . DS . 'View' . DS . strtolower($this->_controller) . DS . 'footer.php')) {
                include (ROOT . DS . 'API' . DS . 'View' . DS . strtolower($this->_controller) . DS . 'footer.php');
            } else {
                include (ROOT . DS . 'API' . DS . 'View' . DS . 'footer.php');
            }
        }
    }
}