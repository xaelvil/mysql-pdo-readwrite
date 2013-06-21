<?php

/**
 * abstract class, services have to extend this one
 *
 * @author xaelvil
 */
require_once 'query.class.php';
abstract class service {

    // Force Extending class to define this method
    abstract public function execService($args);
}

?>
