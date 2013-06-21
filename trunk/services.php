<?php

//get service name and instance class
    $class = (isset($_POST['exec']) ) ? $_POST['exec'] : '';
    $params = (isset($_POST['params']) ) ? $_POST['params'] : '';

    if ($class != null) {
        require_once 'classes/' . $class . '.class.php';
        if (class_exists($class)) {
            $serviceClass = new $class();
            $result = $serviceClass->execService($params);
            echo $result;
        } else {
            echo "Service " . $class . " unknown.";
        }
    }
    
?>