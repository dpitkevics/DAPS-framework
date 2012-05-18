<?php

/**
 * Description of Table
 *
 * @author danpit134
 */
class Table {
    private $_table;
    private $_dbArrayKeys;
    private $_tableParams;
    function set($params = NULL) {
        $this->_table = NULL;
        $this->_dbArrayKeys = NULL;
        $this->_table .= '<table '. $params .'>';
        $this->_tableParams = $params;
        
        return $this;
    }
    
    function addHeadRow($array, $params = NULL) {
        $this->_table .= '<tr '. $params .'>';
        
        if (is_array($array)) {
            foreach ($array as $data) {
                $this->_table .= '<th>';
                $this->_table .= $data;
                $this->_table .= '</th>';
            }
        }
        
        $this->_table .= '</tr>';
        
        return $this;
    }
    
    function addRow($array, $params = NULL) {
        $this->_table .= '<tr '. $params .'>';
        
        if (is_array($array)) {
            foreach($array as $data) {
                $this->_table .= '<td>';
                $this->_table .= $data;
                $this->_table .= '</td>';
            }
        }
        
        $this->_table .= '</tr>';
        
        return $this;
    }
    
    function addDBArray($array, $params = NULL, $exclude = array()) {
        global $cache;
        global $inflect;
        $ucArray = new Arrays();  
        $dataArr = $array;
        //array_shift($dataArr);
        $excludeArr = array();
        
        // Table header from DB
        $tmp;
        $array = $array[0];
        foreach ($array as $key => $value) {
            $tmp = $key;
        }
        
        $name = 'describe'. strtolower($inflect->pluralize($tmp));
        $head = $cache->get($name);
        $header = array();
        foreach ($head as $key => $data) {
            if (!in_array($data, $exclude)) {
                $header[] = $data;
            } else {
                $excludeArr[] = $key;
            }
        }
        $header = $ucArray->upperFirst($header);
        $this->addHeadRow($header, $params);
        
        // Table Data
        foreach($dataArr as $data) {
            foreach($data as $data2) {
                $this->addExcludedRow($data2, $excludeArr);
            }
        }
        
        return $this;
    }
            
    private function addExcludedRow($array, $exclID) {
            $this->_table .= '<tr>';
            $c = -1;
            if (is_array($array)) {
                foreach($array as $key => $data) {
                    $c++;
                    if (!in_array($c, $exclID)) {
                        $this->_table .= '<td>';
                        $this->_table .= $data;
                        $this->_table .= '</td>';
                    }
                }
            }

            $this->_table .= '</tr>';
    }
    
    function finish() {
        $this->_table .= '</table>';
        return $this->_table;
    }
}

