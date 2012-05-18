<?php

class Style {
    function addStyleSheet($name) {
        ob_start();
            echo '<style type="text/css">';
            include ROOT . DS .'Public'. DS .'CSS'. DS . $name;
            echo '</style>';
        $style = ob_get_contents();
        ob_clean();
        
        return $style;
    }
}

?>
