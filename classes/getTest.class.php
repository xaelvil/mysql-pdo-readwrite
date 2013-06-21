<?php

/**
 * Get Charts for an user
 *
 * @author xaelvil
 */
//Including parent class
require_once 'service.class.php';

class getTest extends service {

    public function execService($args) {
        // Here no parameter is needed
        $query = new query();
        $paramVal = (isset($args['id']) ) ? $args['id'] : '';
        $args = array("id" => $paramVal);
        $arr1 = $query->easyQuery('testTable', $args, '*');
        $id = '';
        $temp = array();
        foreach ($arr1 as $value) {
            foreach ($value as $key => $value) {
                if ($key != 'id') {
                    $temp[$key] = $value;
                } else {
                    $id = $value;
                }
            }
            $result[$id] = $temp;
        }    
        return $query->prepareResult($result);
    }

}

?>
