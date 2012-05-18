<?php

/**
 * Description of Form
 *
 * @author danpit134
 */
class Form extends FormModel{
    private $_data;
    private $_name;
    private $_requiredFields;
    private $req;
    
    function set($action = "#", $name = "default") {
        $this->_data = NULL;
        $this->_name = NULL;
        $this->_method = NULL;
        $this->_requiredFields = NULL;
        $this->req = '<span style="color: red;">*</span>';
        $this->_data = '<form action = "'. $action .'" method = "POST" >';
        $this->_name = $name;
        $this->_data .= '<input type="hidden" name="'. $this->_name .'" />';
        
        return $this;
    }
    
    function addField($type = "text", $name = "default", $label = NULL, $value = NULL, $required = false) {
        switch ($type) {
            case "text": 
                if ($required)
                    $this->_requiredFields[] = strtolower($name);
              
                $this->_data .= '<p class="form_'. $name .'"><label for="'. $name .'">'. (($label != NULL)? ucfirst($label) : ucfirst($name)) . (($required)? $this->req : '') .'</label>';
                $this->_data .= '<input type = "text" name = "'. $name .'" value = "'. $value .'" /></p>';
                break;
            case "password":
                if ($required)
                    $this->_requiredFields[] = strtolower($name);
                
                $this->_data .= '<p class="form_'. $name .'"><label for="'. $name .'">'. (($label != NULL)? ucfirst($label) : ucfirst($name)) . (($required)? $this->req : '') .'</label>';
                $this->_data .= '<input type = "password" name = "'. $name .'" value = "'. $value .'" /></p>';
                break;
            case "radio":
                if ($required)
                    $this->_requiredFields[] = strtolower($name);
                
                $id = md5(rand(1, 99999999999));
                $this->_data .= '<p class="form_'. $name .'"><label for="'. $id .'">'. (($label != NULL)? ucfirst($label) : ucfirst($name)) . (($required)? $this->req : '') .'</label>';
                $this->_data .= '<input type = "radio" name = "'. $name .'" value = "'. $value .'" id = "'. $id .'" /></p>';
                break;
            case "checkbox":
                if ($required)
                    $this->_requiredFields[] = strtolower($name);
                
                $id = md5(rand(1, 99999999999));
                $this->_data .= '<p class="form_'. $name .'"><label for="'. $id .'">'. (($label != NULL)? ucfirst($label) : ucfirst($name)) . (($required)? $this->req : '') .'</label>';
                $this->_data .= '<input type = "checkbox" name = "'. $name .'[]" value = "'. $value .'" id = "'. $id .'" /></p>';
                break;
            case "textarea":
                if ($required)
                    $this->_requiredFields[] = strtolower($name);
                
                $this->_data .= '<p class="form_'. $name .'"><label for="'. $name .'">'. (($label != NULL)? ucfirst($label) : ucfirst($name)) . (($required)? $this->req : '') .'</label>';
                $this->_data .= '<textarea name = "'. $name .'">'. $value .'</textarea></p>';
                break;
            default:
                if ($required)
                    $this->_requiredFields[] = strtolower($name);
                
                $this->_data .= '<p class="form_'. $name .'"><label for="'. $name .'">'. (($label != NULL)? ucfirst($label) : ucfirst($name)) . (($required)? $this->req : '') .'</label>';
                $this->_data .= '<input type = "text" name = "'. $name .'" value = "'. $value .'" /></p>';
                break;
        }
        return $this;
    }
    
    function addSubmit($name = "save", $value = NULL) {
        $this->_data .= '<input type = "submit" name = "'. $name .'" value = "'. (($value != NULL)? ucfirst($value) : ucfirst($name)) .'" />';
        return $this;
    }
    
    function addReset($name = "reset", $value = NULL) {
        $this->_data .= '<input type = "reset" name = "'. $name .'" value = "'. (($value != NULL)? ucfirst($value) : ucfirst($name)) .'" />';
        return $this;
    }
    
    function finish() {
        $this->_data .= '</form>';
        return $this->_data;
    }
    
    function isSubmitted() {
        $error = 1;
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $error = 0;
            foreach ($_POST as $key => $value) {
                foreach ($this->_requiredFields as $data) {
                    if ($key == $data) {
                        if (empty($value)) {
                            $error = 1;
                            break;
                        }
                    }
                }
            }
        }
        if ($error)
            return false;
        else 
            return true;
    }
    
    function updateDB() {
        $fieldArray = array();
        foreach ($_POST as $key => $value) {
            if (is_array($value))
                $this->$key = implode(',', $value);
            else
                $this->$key = $value;
            $fieldArray[] = $this->$key;
        }
        $this->save($fieldArray);
    }
    
    function getAsArray() {
        $fieldArray = array();
        foreach ($_POST as $key => $value) {
            if (is_array($value))
                $this->$key = implode(',', $value);
            else
                $this->$key = $value;
            $fieldArray[$key] = $this->$key;
        }
        
        return $fieldArray;
    }
    
    function __destruct() {
        $this->_data = NULL;
        $this->_name = NULL;
    }
}

