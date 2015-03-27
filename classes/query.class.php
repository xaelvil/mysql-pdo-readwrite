<?php

/**
 * Class for querys to the database
 *
 * @author xaelvil
 */
require_once 'db.class.php';
class query {

    private $pdo = '';
    private $host = '';
    private $user = '';
    private $pass = '';
    private $databaseName = '';
    private $opt = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => FALSE,
    );
    private $paramsName = array();
    private $paramsValue = array();

    public function __construct() {
        $db = new db();
        $this->host = $db->getHost();
        $this->user = $db->getUser();
        $this->pass = $db->getPass();
        $this->databaseName = $db->getDatabaseName();
        $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->databaseName . ";charset=utf8";
        $this->pdo = new PDO($dsn, $this->user, $this->pass, $this->opt);
        $this->paramsName = array();
        $this->paramsValue = array();
    }

    private function fillParams($params) {

        foreach ($params as $key => $value) {
            array_push($this->paramsName, $key);
            array_push($this->paramsValue, $value);
        }
        return sizeof($params);
    }

    public function getCount($table) {

        $q = 'select count(*) from ' . $table;
        $query = $this->pdo->prepare($q);
        $query->execute();
        $return = $query->fetchAll();
        if (is_array($return) && isset($return[0]['count(*)']))
            return $return[0]['count(*)'];
    }

    public function getCountWParams($table_name, $params) {

        $q = 'select count(*) from ' . $table_name;

        if (is_array($params))
            $size = self::fillParams($params);

        switch ($size) {
            case 1:
                $q = $q . ' where ' . $this->paramsName[0] . ' = ?';
                break;
            case 2:
                $q = $q . ' where ' . $this->paramsName[0] . ' = ? and ' . $this->paramsName[1] . ' = ?';
                break;
            case 3:
                $q = $q . ' where ' . $this->paramsName[0] . ' = ? and ' . $this->paramsName[1] . ' = ? and '
                        . $this->paramsName[2] . ' = ?';
                break;
            case 4:
                $q = $q . ' where ' . $this->paramsName[0] . ' = ? and ' . $this->paramsName[1] . ' = ? and '
                        . $this->paramsName[2] . ' = ? and ' . $this->paramsName[3] . ' = ?';
                break;
            case 5:
                $q = $q . ' where ' . $this->paramsName[0] . ' = ? and ' . $this->paramsName[1] . ' = ? and '
                        . $this->paramsName[2] . ' = ? and ' . $this->paramsName[3] . ' = ? and ' . $this->paramsName[4] . ' = ?';
                break;
        }

        $query = $this->pdo->prepare($q);
        $query->execute($this->paramsValue);
        $this->paramsName = array();
        $this->paramsValue = array();
        $return = $query->fetchAll();
        if (is_array($return) && isset($return[0]['count(*)']))
            return $return[0]['count(*)'];
    }

    public function prepareResult($result, $out = true) {
        $result = (isset($result) ) ? $result : '';
        if ($out) {
            return '{"records":' . json_encode($result) . '}';
        } else {
            return $result;
        }
    }

    public function easyQuery($table_name, $params, $get) {

        $q = 'SELECT ' . $get . ' from ' . $table_name;

        if (is_array($params))
            $size = self::fillParams($params);
        switch ($size) {
            case 1:
                $q = $q . ' where ' . $this->paramsName[0] . ' = ?';
                break;
            case 2:
                $q = $q . ' where ' . $this->paramsName[0] . ' = ? and ' . $this->paramsName[1] . ' = ?';
                break;
            case 3:
                $q = $q . ' where ' . $this->paramsName[0] . ' = ? and ' . $this->paramsName[1] . ' = ? and '
                        . $this->paramsName[2] . ' = ?';
                break;
            case 4:
                $q = $q . ' where ' . $this->paramsName[0] . ' = ? and ' . $this->paramsName[1] . ' = ? and '
                        . $this->paramsName[2] . ' = ? and ' . $this->paramsName[3] . ' = ?';
                break;
            case 5:
                $q = $q . ' where ' . $this->paramsName[0] . ' = ? and ' . $this->paramsName[1] . ' = ? and '
                        . $this->paramsName[2] . ' = ? and ' . $this->paramsName[3] . ' = ? and ' . $this->paramsName[4] . ' = ?';
                break;
        }

        $query = $this->pdo->prepare($q);
        $query->execute($this->paramsValue);
        $this->paramsName = array();
        $this->paramsValue = array();
        return $query->fetchAll();
    }

    public function deleteWhere($table_name, $params) {

        $q = 'DELETE FROM ' . $table_name;

        if (is_array($params))
            $size = self::fillParams($params);

        switch ($size) {
            case 1:
                $q = $q . ' where ' . $this->paramsName[0] . ' = ?';
                break;
            case 2:
                $q = $q . ' where ' . $this->paramsName[0] . ' = ? and ' . $this->paramsName[1] . ' = ?';
                break;
            case 3:
                $q = $q . ' where ' . $this->paramsName[0] . ' = ? and ' . $this->paramsName[1] . ' = ? and '
                        . $this->paramsName[2] . ' = ?';
                break;
            case 4:
                $q = $q . ' where ' . $this->paramsName[0] . ' = ? and ' . $this->paramsName[1] . ' = ? and '
                        . $this->paramsName[2] . ' = ? and ' . $this->paramsName[3] . ' = ?';
                break;
            case 5:
                $q = $q . ' where ' . $this->paramsName[0] . ' = ? and ' . $this->paramsName[1] . ' = ? and '
                        . $this->paramsName[2] . ' = ? and ' . $this->paramsName[3] . ' = ? and ' . $this->paramsName[4] . ' = ?';
                break;
        }

        $query = $this->pdo->prepare($q);
        $query->execute($this->paramsValue);
        $this->paramsName = array();
        $this->paramsValue = array();
        return $query->fetchAll();
    }

    /*
     * 
      $args1 = array("customer_id" => $customer);
      $args2 = array("id" => $nodeId);
      $result = $query->join2Query('company', 'node', 'a.chart_id = b.chart_id', $args1, $args2, 'id', 'name|car');
     * 
     */

    public function join2Query($table1_name, $table2_name, $cond, $params1, $params2, $get1, $get2) {
        //select join, no * allowed
        $q = 'SELECT ';

        //params to get
        $i = 0;
        if ($get1) {
            $arr1 = explode("|", $get1);
            $len = sizeof($arr1);
            foreach ($arr1 as &$value) {
                $i++;
                if ($i == $len) {
                    $q = $q . 'a.' . $value . ' ';
                } else {
                    $q = $q . 'a.' . $value . ', ';
                }
            }
        }

        if ($get2) {
            $arr2 = explode("|", $get2);
            $len = sizeof($arr2);
            $j = 1;
            foreach ($arr2 as &$value) {
                if ($i != 0 && $j != 0) {
                    $q = $q . ', ';
                    $j = $i = 0;
                }
                $i++;
                $j = 0;
                if ($i == $len) {
                    $q = $q . 'b.' . $value . ' ';
                } else {
                    $q = $q . 'b.' . $value . ', ';
                }
            }
            unset($value);
            unset($i);
            unset($j);
        }

        $q = $q . 'from ' . $table1_name . ' a, ' . $table2_name . ' b ';
        if (is_array($params1)) {
            $size1 = sizeof($params1);
            $i = 1;
            foreach ($params1 as $key => $value) {
                switch ($i) {
                    case 1:
                        $param11_n = $key;
                        $param11_v = $value;
                        break;

                    case 2:
                        $param12_n = $key;
                        $param12_v = $value;
                        break;
                    case 3:
                        $param13_n = $key;
                        $param13_v = $value;
                        break;
                    case 4:
                        $param14_n = $key;
                        $param14_v = $value;
                        break;
                    case 5:
                        $param15_n = $key;
                        $param15_v = $value;
                        break;
                }
                $i++;
            }
            unset($key);
            unset($value);
            unset($i);
        } else {
            $size1 = 0;
        }


        if (is_array($params2)) {
            $size2 = sizeof($params2);
            $i = 1;
            foreach ($params2 as $key => $value) {
                switch ($i) {
                    case 1:
                        $param21_n = $key;
                        $param21_v = $value;
                        break;

                    case 2:
                        $param22_n = $key;
                        $param22_v = $value;
                        break;
                    case 3:
                        $param23_n = $key;
                        $param23_v = $value;
                        break;
                    case 4:
                        $param24_n = $key;
                        $param24_v = $value;
                        break;
                    case 5:
                        $param25_n = $key;
                        $param25_v = $value;
                        break;
                }
                $i++;
            }
        } else {
            $size2 = 0;
        }

        unset($key);
        unset($value);
        unset($i);

        $q = $q . ' where ' . $cond . ' ';

        switch ($size1) {
            case 1:
                $q = $q . ' and a.' . $param11_n . ' = ?';
                break;
            case 2:
                $q = $q . ' and a.' . $param11_n . ' = ? and a.' . $param12_n . ' = ?';
                break;
            case 3:
                $q = $q . ' and a.' . $param11_n . ' = ? and a.' . $param12_n . ' = ? and a.'
                        . $param13_n . ' = ?';
                break;
            case 4:
                $q = $q . ' and a.' . $param11_n . ' = ? and a.' . $param12_n . ' = ? and a.'
                        . $param13_n . ' = ? and a.' . $param14_n . ' = ?';
                break;
            case 5:
                $q = $q . ' and a.' . $param11_n . ' = ? and a.' . $param12_n . ' = ? and a.'
                        . $param13_n . ' = ? and a.' . $param14_n . ' = ? and a.' . $param15_n . ' = ?';
                break;
            default:
                break;
        }

        switch ($size2) {
            case 1:
                $q = $q . ' and b.' . $param21_n . ' = ?';
                $query = $this->pdo->prepare($q);
                switch ($size1) {
                    case 1:
                        $query->execute(array($param11_v, $param21_v));
                        break;
                    case 2:
                        $query->execute(array($param11_v, $param12_v, $param21_v));
                        break;
                    case 3:
                        $query->execute(array($param11_v, $param12_v, $param13_v, $param21_v));
                        break;
                    case 4:
                        $query->execute(array($param11_v, $param12_v, $param13_v, $param14_v, $param21_v));
                        break;
                    case 5:
                        $query->execute(array($param11_v, $param12_v, $param13_v, $param14_v, $param15_v, $param21_v));
                        break;
                    default:
                        $query->execute(array($param21_v));
                        break;
                }
                break;
            case 2:
                $q = $q . ' and b.' . $param21_n . ' = ? and b.' . $param22_n . ' = ?';
                $query = $this->pdo->prepare($q);
                switch ($size1) {
                    case 1:
                        $query->execute(array($param11_v, $param21_v, $param22_v));
                        break;
                    case 2:
                        $query->execute(array($param11_v, $param12_v, $param21_v, $param22_v));
                        break;
                    case 3:
                        $query->execute(array($param11_v, $param12_v, $param13_v, $param21_v, $param22_v));
                        break;
                    case 4:
                        $query->execute(array($param11_v, $param12_v, $param13_v, $param14_v, $param21_v, $param22_v));
                        break;
                    case 5:
                        $query->execute(array($param11_v, $param12_v, $param13_v, $param14_v, $param15_v, $param21_v, $param22_v));
                        break;
                    default:
                        $query->execute(array($param21_v, $param22_v));
                        break;
                }
                break;
            case 3:
                $q = $q . ' and b.' . $param21_n . ' = ? and b.' . $param22_n . ' = ? and b.'
                        . $param23_n . ' = ?';
                $query = $this->pdo->prepare($q);
                switch ($size1) {
                    case 1:
                        $query->execute(array($param11_v, $param21_v, $param22_v, $param23_v));
                        break;
                    case 2:
                        $query->execute(array($param11_v, $param12_v, $param21_v, $param22_v, $param23_v));
                        break;
                    case 3:
                        $query->execute(array($param11_v, $param12_v, $param13_v, $param21_v, $param22_v, $param23_v));
                        break;
                    case 4:
                        $query->execute(array($param11_v, $param12_v, $param13_v, $param14_v, $param21_v, $param22_v, $param23_v));
                        break;
                    case 5:
                        $query->execute(array($param11_v, $param12_v, $param13_v, $param14_v, $param15_v, $param21_v, $param22_v, $param23_v));
                        break;
                    default:
                        $query->execute(array($param21_v, $param22_v, $param23_v));
                        break;
                }
                break;
            case 4:
                $q = $q . ' and b.' . $param21_n . ' = ? and b.' . $param22_n . ' = ? and b.'
                        . $param23_n . ' = ? and b.' . $param24_n . ' = ?';
                $query = $this->pdo->prepare($q);
                switch ($size1) {
                    case 1:
                        $query->execute(array($param11_v, $param21_v, $param22_v, $param23_v, $param24_v));
                        break;
                    case 2:
                        $query->execute(array($param11_v, $param12_v, $param21_v, $param22_v, $param23_v, $param24_v));
                        break;
                    case 3:
                        $query->execute(array($param11_v, $param12_v, $param13_v, $param21_v, $param22_v, $param23_v, $param24_v));
                        break;
                    case 4:
                        $query->execute(array($param11_v, $param12_v, $param13_v, $param14_v, $param21_v, $param22_v, $param23_v, $param24_v));
                        break;
                    case 5:
                        $query->execute(array($param11_v, $param12_v, $param13_v, $param14_v, $param15_v, $param21_v, $param22_v, $param23_v, $param24_v));
                        break;
                    default:
                        $query->execute(array($param21_v, $param22_v, $param23_v, $param24_v));
                        break;
                }
                break;
            case 5:
                $q = $q . ' and b.' . $param21_n . ' = ? and b.' . $param22_n . ' = ? and b.'
                        . $param23_n . ' = ? and b.' . $param24_n . ' = ? and b.' . $param25_n . ' = ?';
                $query = $this->pdo->prepare($q);
                switch ($size1) {
                    case 1:
                        $query->execute(array($param11_v, $param21_v, $param22_v, $param23_v, $param24_v, $param25_v));
                        break;
                    case 2:
                        $query->execute(array($param11_v, $param12_v, $param21_v, $param22_v, $param23_v, $param24_v, $param25_v));
                        break;
                    case 3:
                        $query->execute(array($param11_v, $param12_v, $param13_v, $param21_v, $param22_v, $param23_v, $param24_v, $param25_v));
                        break;
                    case 4:
                        $query->execute(array($param11_v, $param12_v, $param13_v, $param14_v, $param21_v, $param22_v, $param23_v, $param24_v, $param25_v));
                        break;
                    case 5:
                        $query->execute(array($param11_v, $param12_v, $param13_v, $param14_v, $param15_v, $param21_v, $param22_v, $param23_v, $param24_v, $param25_v));
                        break;
                    default:
                        $query->execute(array($param21_v, $param22_v, $param23_v, $param24_v, $param25_v));
                        break;
                }
                break;
            default:
                $query = $this->pdo->prepare($q);
                switch ($size1) {
                    case 1:
                        $query->execute(array($param11_v));
                        break;
                    case 2:
                        $query->execute(array($param11_v, $param12_v));
                        break;
                    case 3:
                        $query->execute(array($param11_v, $param12_v, $param13_v));
                        break;
                    case 4:
                        $query->execute(array($param11_v, $param12_v, $param13_v, $param14_v));
                        break;
                    case 5:
                        $query->execute(array($param11_v, $param12_v, $param13_v, $param14_v, $param15_v));
                        break;
                    default:
                        $query->execute();
                        break;
                }
                break;
        }
        return $query->fetchAll();
    }

    public function update($table, $fields, $where) {
        if (is_array($fields)) {
            $size = sizeof($fields);
            $i = 1;
            foreach ($fields as $key => $value) {
                switch ($i) {
                    case 1:
                        $param1_n = $key;
                        $param1_v = $value;
                        break;
                    case 2:
                        $param2_n = $key;
                        $param2_v = $value;
                        break;
                    case 3:
                        $param3_n = $key;
                        $param3_v = $value;
                        break;
                    case 4:
                        $param4_n = $key;
                        $param4_v = $value;
                        break;
                    case 5:
                        $param5_n = $key;
                        $param5_v = $value;
                        break;
                    case 6:
                        $param6_n = $key;
                        $param6_v = $value;
                        break;
                    case 7:
                        $param7_n = $key;
                        $param7_v = $value;
                        break;
                    case 8:
                        $param8_n = $key;
                        $param8_v = $value;
                        break;
                    case 9:
                        $param9_n = $key;
                        $param9_v = $value;
                        break;
                    case 10:
                        $param10_n = $key;
                        $param10_v = $value;
                        break;
                }
                $i++;
            }
            unset($key);
            unset($value);
            unset($i);
        } else {
            return;
        }
        if (is_array($where)) {
            $sizeWhere = sizeof($where);
            $i = 1;
            foreach ($where as $key => $value) {
                switch ($i) {
                    case 1:
                        $where1_n = $key;
                        $where1_v = $value;
                        break;
                    case 2:
                        $where2_n = $key;
                        $where2_v = $value;
                        break;
                    case 3:
                        $where3_n = $key;
                        $where3_v = $value;
                        break;
                    case 4:
                        $where4_n = $key;
                        $where4_v = $value;
                        break;
                    case 5:
                        $where5_n = $key;
                        $where5_v = $value;
                        break;
                }
                $i++;
            }
            unset($key);
            unset($value);
            unset($i);
        } else {
            return;
        }
        $q = 'UPDATE ' . $table . ' SET ';
        switch ($size) {
            case 1:
                $q = $q . $param1_n . ' = ?';
                $q = $q . ' WHERE ';
                switch ($sizeWhere) {
                    case 1:
                        $q = $q . $where1_n . ' = ?';
                        $query = $this->pdo->prepare($q);
                        return $query->execute(array($param1_v, $where1_v));
                        break;
                    case 2:
                        $q = $q . $where1_n . ' = ? and ' . $where2_n . ' = ?';
                        $query = $this->pdo->prepare($q);
                        return $query->execute(array($param1_v, $where1_v, $where2_v));
                        break;
                    case 3:
                        $q = $q . $where1_n . ' = ? and ' . $where2_n . ' = ? and ' . $where3_n . ' = ?';
                        $query = $this->pdo->prepare($q);
                        return $query->execute(array($param1_v, $where1_v, $where2_v, $where3_v));
                        break;
                    case 4:
                        $q = $q . $where1_n . ' = ? and ' . $where2_n . ' = ? and ' . $where3_n . ' = ? and ' . $where4_n . ' = ?';
                        $query = $this->pdo->prepare($q);
                        return $query->execute(array($param1_v, $where1_v, $where2_v, $where3_v, $where4_v));
                        break;
                    case 5:
                        $q = $q . $where1_n . ' = ? and ' . $where2_n . ' = ? and ' . $where3_n . ' = ? and ' . $where4_n . ' = ? and ' . $where5_n . ' = ?';
                        $query = $this->pdo->prepare($q);
                        return $query->execute(array($param1_v, $where1_v, $where2_v, $where3_v, $where4_v, $where5_v));
                        break;
                    default:
                        return;
                }
                break;
            case 2:
                $q = $q . $param1_n . ' = ? , ' . $param2_n . ' = ? ';
                $q = $q . ' WHERE ';
                switch ($sizeWhere) {
                    case 1:
                        $q = $q . $where1_n . ' = ?';
                        $query = $this->pdo->prepare($q);
                        return $query->execute(array($param1_v, $param2_v, $where1_v));
                        break;
                    case 2:
                        $q = $q . $where1_n . ' = ? and ' . $where2_n . ' = ?';
                        $query = $this->pdo->prepare($q);
                        return $query->execute(array($param1_v, $param2_v, $where1_v, $where2_v));
                        break;
                    case 3:
                        $q = $q . $where1_n . ' = ? and ' . $where2_n . ' = ? and ' . $where3_n . ' = ?';
                        $query = $this->pdo->prepare($q);
                        return $query->execute(array($param1_v, $param2_v, $where1_v, $where2_v, $where3_v));
                        break;
                    case 4:
                        $q = $q . $where1_n . ' = ? and ' . $where2_n . ' = ? and ' . $where3_n . ' = ? and ' . $where4_n . ' = ?';
                        $query = $this->pdo->prepare($q);
                        return $query->execute(array($param1_v, $param2_v, $where1_v, $where2_v, $where3_v, $where4_v));
                        break;
                    case 5:
                        $q = $q . $where1_n . ' = ? and ' . $where2_n . ' = ? and ' . $where3_n . ' = ? and ' . $where4_n . ' = ? and ' . $where5_n . ' = ?';
                        $query = $this->pdo->prepare($q);
                        return $query->execute(array($param1_v, $param2_v, $where1_v, $where2_v, $where3_v, $where4_v, $where5_v));
                        break;
                    default:
                        return;
                }
                break;
            case 3:
                $q = $q . $param1_n . ' = ? , ' . $param2_n . ' = ? , ' . $param3_n . ' = ? ';
                $q = $q . ' WHERE ';
                switch ($sizeWhere) {
                    case 1:
                        $q = $q . $where1_n . ' = ?';
                        $query = $this->pdo->prepare($q);
                        return $query->execute(array($param1_v, $param2_v, $param3_v, $where1_v));
                        break;
                    case 2:
                        $q = $q . $where1_n . ' = ? and ' . $where2_n . ' = ?';
                        $query = $this->pdo->prepare($q);
                        return $query->execute(array($param1_v, $param2_v, $param3_v, $where1_v, $where2_v));
                        break;
                    case 3:
                        $q = $q . $where1_n . ' = ? and ' . $where2_n . ' = ? and ' . $where3_n . ' = ?';
                        $query = $this->pdo->prepare($q);
                        return $query->execute(array($param1_v, $param2_v, $param3_v, $where1_v, $where2_v, $where3_v));
                        break;
                    case 4:
                        $q = $q . $where1_n . ' = ? and ' . $where2_n . ' = ? and ' . $where3_n . ' = ? and ' . $where4_n . ' = ?';
                        $query = $this->pdo->prepare($q);
                        return $query->execute(array($param1_v, $param2_v, $param3_v, $where1_v, $where2_v, $where3_v, $where4_v));
                        break;
                    case 5:
                        $q = $q . $where1_n . ' = ? and ' . $where2_n . ' = ? and ' . $where3_n . ' = ? and ' . $where4_n . ' = ? and ' . $where5_n . ' = ?';
                        $query = $this->pdo->prepare($q);
                        return $query->execute(array($param1_v, $param2_v, $param3_v, $where1_v, $where2_v, $where3_v, $where4_v, $where5_v));
                        break;
                    default:
                        return;
                }
                break;
            case 4:
                $q = $q . $param1_n . ' = ? , ' . $param2_n . ' = ? , ' . $param3_n . ' = ? , ' . $param4_n . ' = ? ';
                $q = $q . ' WHERE ';
                switch ($sizeWhere) {
                    case 1:
                        $q = $q . $where1_n . ' = ?';
                        $query = $this->pdo->prepare($q);
                        return $query->execute(array($param1_v, $param2_v, $param3_v, $param4_v, $where1_v));
                        break;
                    case 2:
                        $q = $q . $where1_n . ' = ? and ' . $where2_n . ' = ?';
                        $query = $this->pdo->prepare($q);
                        return $query->execute(array($param1_v, $param2_v, $param3_v, $param4_v, $where1_v, $where2_v));
                        break;
                    case 3:
                        $q = $q . $where1_n . ' = ? and ' . $where2_n . ' = ? and ' . $where3_n . ' = ?';
                        $query = $this->pdo->prepare($q);
                        return $query->execute(array($param1_v, $param2_v, $param3_v, $param4_v, $where1_v, $where2_v, $where3_v));
                        break;
                    case 4:
                        $q = $q . $where1_n . ' = ? and ' . $where2_n . ' = ? and ' . $where3_n . ' = ? and ' . $where4_n . ' = ?';
                        $query = $this->pdo->prepare($q);
                        return $query->execute(array($param1_v, $param2_v, $param3_v, $param4_v, $where1_v, $where2_v, $where3_v, $where4_v));
                        break;
                    case 5:
                        $q = $q . $where1_n . ' = ? and ' . $where2_n . ' = ? and ' . $where3_n . ' = ? and ' . $where4_n . ' = ? and ' . $where5_n . ' = ?';
                        $query = $this->pdo->prepare($q);
                        return $query->execute(array($param1_v, $param2_v, $param3_v, $param4_v, $where1_v, $where2_v, $where3_v, $where4_v, $where5_v));
                        break;
                    default:
                        return;
                }
                break;
            case 5:
                $q = $q . $param1_n . ' = ? , ' . $param2_n . ' = ? , ' . $param3_n . ' = ? , ' . $param4_n . ' = ? , ' . $param5_n . ' = ? ';
                $q = $q . ' WHERE ';
                switch ($sizeWhere) {
                    case 1:
                        $q = $q . $where1_n . ' = ?';
                        $query = $this->pdo->prepare($q);
                        return $query->execute(array($param1_v, $param2_v, $param3_v, $param4_v, $param5_v, $where1_v));
                        break;
                    case 2:
                        $q = $q . $where1_n . ' = ? and ' . $where2_n . ' = ?';
                        $query = $this->pdo->prepare($q);
                        return $query->execute(array($param1_v, $param2_v, $param3_v, $param4_v, $param5_v, $where1_v, $where2_v));
                        break;
                    case 3:
                        $q = $q . $where1_n . ' = ? and ' . $where2_n . ' = ? and ' . $where3_n . ' = ?';
                        $query = $this->pdo->prepare($q);
                        return $query->execute(array($param1_v, $param2_v, $param3_v, $param4_v, $param5_v, $where1_v, $where2_v, $where3_v));
                        break;
                    case 4:
                        $q = $q . $where1_n . ' = ? and ' . $where2_n . ' = ? and ' . $where3_n . ' = ? and ' . $where4_n . ' = ?';
                        $query = $this->pdo->prepare($q);
                        return $query->execute(array($param1_v, $param2_v, $param3_v, $param4_v, $param5_v, $where1_v, $where2_v, $where3_v, $where4_v));
                        break;
                    case 5:
                        $q = $q . $where1_n . ' = ? and ' . $where2_n . ' = ? and ' . $where3_n . ' = ? and ' . $where4_n . ' = ? and ' . $where5_n . ' = ?';
                        $query = $this->pdo->prepare($q);
                        return $query->execute(array($param1_v, $param2_v, $param3_v, $param4_v, $param5_v, $where1_v, $where2_v, $where3_v, $where4_v, $where5_v));
                        break;
                    default:
                        return;
                }
                break;
            case 6:
                $q = $q . $param1_n . ' = ? , ' . $param2_n . ' = ? , ' . $param3_n . ' = ? , ' . $param4_n . ' = ? , ' . $param5_n . ' = ? , ' . $param6_n . ' = ? ';
                $q = $q . ' WHERE ';
                switch ($sizeWhere) {
                    case 1:
                        $q = $q . $where1_n . ' = ?';
                        $query = $this->pdo->prepare($q);
                        return $query->execute(array($param1_v, $param2_v, $param3_v, $param4_v, $param5_v, $param6_v, $where1_v));
                        break;
                    case 2:
                        $q = $q . $where1_n . ' = ? and ' . $where2_n . ' = ?';
                        $query = $this->pdo->prepare($q);
                        return $query->execute(array($param1_v, $param2_v, $param3_v, $param4_v, $param5_v, $param6_v, $where1_v, $where2_v));
                        break;
                    case 3:
                        $q = $q . $where1_n . ' = ? and ' . $where2_n . ' = ? and ' . $where3_n . ' = ?';
                        $query = $this->pdo->prepare($q);
                        return $query->execute(array($param1_v, $param2_v, $param3_v, $param4_v, $param5_v, $param6_v, $where1_v, $where2_v, $where3_v));
                        break;
                    case 4:
                        $q = $q . $where1_n . ' = ? and ' . $where2_n . ' = ? and ' . $where3_n . ' = ? and ' . $where4_n . ' = ?';
                        $query = $this->pdo->prepare($q);
                        return $query->execute(array($param1_v, $param2_v, $param3_v, $param4_v, $param5_v, $param6_v, $where1_v, $where2_v, $where3_v, $where4_v));
                        break;
                    case 5:
                        $q = $q . $where1_n . ' = ? and ' . $where2_n . ' = ? and ' . $where3_n . ' = ? and ' . $where4_n . ' = ? and ' . $where5_n . ' = ?';
                        $query = $this->pdo->prepare($q);
                        return $query->execute(array($param1_v, $param2_v, $param3_v, $param4_v, $param5_v, $param6_v, $where1_v, $where2_v, $where3_v, $where4_v, $where5_v));
                        break;
                    default:
                        return;
                }
                break;
            case 7:
                $q = $q . $param1_n . ' = ? , ' . $param2_n . ' = ? , ' . $param3_n . ' = ? , ' . $param4_n . ' = ? , ' . $param5_n . ' = ? , ' . $param6_n . ' = ? , ' . $param7_n . ' = ? ';
                $q = $q . ' WHERE ';
                switch ($sizeWhere) {
                    case 1:
                        $q = $q . $where1_n . ' = ?';
                        $query = $this->pdo->prepare($q);
                        return $query->execute(array($param1_v, $param2_v, $param3_v, $param4_v, $param5_v, $param6_v, $param7_v, $where1_v));
                        break;
                    case 2:
                        $q = $q . $where1_n . ' = ? and ' . $where2_n . ' = ?';
                        $query = $this->pdo->prepare($q);
                        return $query->execute(array($param1_v, $param2_v, $param3_v, $param4_v, $param5_v, $param6_v, $param7_v, $where1_v, $where2_v));
                        break;
                    case 3:
                        $q = $q . $where1_n . ' = ? and ' . $where2_n . ' = ? and ' . $where3_n . ' = ?';
                        $query = $this->pdo->prepare($q);
                        return $query->execute(array($param1_v, $param2_v, $param3_v, $param4_v, $param5_v, $param6_v, $param7_v, $where1_v, $where2_v, $where3_v));
                        break;
                    case 4:
                        $q = $q . $where1_n . ' = ? and ' . $where2_n . ' = ? and ' . $where3_n . ' = ? and ' . $where4_n . ' = ?';
                        $query = $this->pdo->prepare($q);
                        return $query->execute(array($param1_v, $param2_v, $param3_v, $param4_v, $param5_v, $param6_v, $param7_v, $where1_v, $where2_v, $where3_v, $where4_v));
                        break;
                    case 5:
                        $q = $q . $where1_n . ' = ? and ' . $where2_n . ' = ? and ' . $where3_n . ' = ? and ' . $where4_n . ' = ? and ' . $where5_n . ' = ?';
                        $query = $this->pdo->prepare($q);
                        return $query->execute(array($param1_v, $param2_v, $param3_v, $param4_v, $param5_v, $param6_v, $param7_v, $where1_v, $where2_v, $where3_v, $where4_v, $where5_v));
                        break;
                    default:
                        return;
                }
                break;
            case 8:
                $q = $q . $param1_n . ' = ? , ' . $param2_n . ' = ? , ' . $param3_n . ' = ? , ' . $param4_n . ' = ? , ' . $param5_n . ' = ? , ' . $param6_n . ' = ? , ' . $param7_n . ' = ? , ' . $param8_n . ' = ? ';
                $q = $q . ' WHERE ';
                switch ($sizeWhere) {
                    case 1:
                        $q = $q . $where1_n . ' = ?';
                        $query = $this->pdo->prepare($q);
                        return $query->execute(array($param1_v, $param2_v, $param3_v, $param4_v, $param5_v, $param6_v, $param7_v, $param8_v, $where1_v));
                        break;
                    case 2:
                        $q = $q . $where1_n . ' = ? and ' . $where2_n . ' = ?';
                        $query = $this->pdo->prepare($q);
                        return $query->execute(array($param1_v, $param2_v, $param3_v, $param4_v, $param5_v, $param6_v, $param7_v, $param8_v, $where1_v, $where2_v));
                        break;
                    case 3:
                        $q = $q . $where1_n . ' = ? and ' . $where2_n . ' = ? and ' . $where3_n . ' = ?';
                        $query = $this->pdo->prepare($q);
                        return $query->execute(array($param1_v, $param2_v, $param3_v, $param4_v, $param5_v, $param6_v, $param7_v, $param8_v, $where1_v, $where2_v, $where3_v));
                        break;
                    case 4:
                        $q = $q . $where1_n . ' = ? and ' . $where2_n . ' = ? and ' . $where3_n . ' = ? and ' . $where4_n . ' = ?';
                        $query = $this->pdo->prepare($q);
                        return $query->execute(array($param1_v, $param2_v, $param3_v, $param4_v, $param5_v, $param6_v, $param7_v, $param8_v, $where1_v, $where2_v, $where3_v, $where4_v));
                        break;
                    case 5:
                        $q = $q . $where1_n . ' = ? and ' . $where2_n . ' = ? and ' . $where3_n . ' = ? and ' . $where4_n . ' = ? and ' . $where5_n . ' = ?';
                        $query = $this->pdo->prepare($q);
                        return $query->execute(array($param1_v, $param2_v, $param3_v, $param4_v, $param5_v, $param6_v, $param7_v, $param8_v, $where1_v, $where2_v, $where3_v, $where4_v, $where5_v));
                        break;
                    default:
                        return;
                }
                break;
            case 9:
                $q = $q . $param1_n . ' = ? , ' . $param2_n . ' = ? , ' . $param3_n . ' = ? , ' . $param4_n . ' = ? , ' . $param5_n . ' = ? , ' . $param6_n . ' = ? , ' . $param7_n . ' = ? , ' . $param8_n . ' = ? , ' . $param9_n . ' = ? ';
                $q = $q . ' WHERE ';
                switch ($sizeWhere) {
                    case 1:
                        $q = $q . $where1_n . ' = ?';
                        $query = $this->pdo->prepare($q);
                        return $query->execute(array($param1_v, $param2_v, $param3_v, $param4_v, $param5_v, $param6_v, $param7_v, $param8_v, $param9_v, $where1_v));
                        break;
                    case 2:
                        $q = $q . $where1_n . ' = ? and ' . $where2_n . ' = ?';
                        $query = $this->pdo->prepare($q);
                        return $query->execute(array($param1_v, $param2_v, $param3_v, $param4_v, $param5_v, $param6_v, $param7_v, $param8_v, $param9_v, $where1_v, $where2_v));
                        break;
                    case 3:
                        $q = $q . $where1_n . ' = ? and ' . $where2_n . ' = ? and ' . $where3_n . ' = ?';
                        $query = $this->pdo->prepare($q);
                        return $query->execute(array($param1_v, $param2_v, $param3_v, $param4_v, $param5_v, $param6_v, $param7_v, $param8_v, $param9_v, $where1_v, $where2_v, $where3_v));
                        break;
                    case 4:
                        $q = $q . $where1_n . ' = ? and ' . $where2_n . ' = ? and ' . $where3_n . ' = ? and ' . $where4_n . ' = ?';
                        $query = $this->pdo->prepare($q);
                        return $query->execute(array($param1_v, $param2_v, $param3_v, $param4_v, $param5_v, $param6_v, $param7_v, $param8_v, $param9_v, $where1_v, $where2_v, $where3_v, $where4_v));
                        break;
                    case 5:
                        $q = $q . $where1_n . ' = ? and ' . $where2_n . ' = ? and ' . $where3_n . ' = ? and ' . $where4_n . ' = ? and ' . $where5_n . ' = ?';
                        $query = $this->pdo->prepare($q);
                        return $query->execute(array($param1_v, $param2_v, $param3_v, $param4_v, $param5_v, $param6_v, $param7_v, $param8_v, $param9_v, $where1_v, $where2_v, $where3_v, $where4_v, $where5_v));
                        break;
                    default:
                        return;
                }
                break;
            case 10:
                $q = $q . $param1_n . ' = ? , ' . $param2_n . ' = ? , ' . $param3_n . ' = ? , ' . $param4_n . ' = ? , ' . $param5_n . ' = ? , ' . $param6_n . ' = ? , ' . $param7_n . ' = ? , ' . $param8_n . ' = ? , ' . $param9_n . ' = ? , ' . $param10_n . ' = ? ';
                $q = $q . ' WHERE ';
                switch ($sizeWhere) {
                    case 1:
                        $q = $q . $where1_n . ' = ?';
                        $query = $this->pdo->prepare($q);
                        return $query->execute(array($param1_v, $param2_v, $param3_v, $param4_v, $param5_v, $param6_v, $param7_v, $param8_v, $param9_v, $param10_v, $where1_v));
                        break;
                    case 2:
                        $q = $q . $where1_n . ' = ? and ' . $where2_n . ' = ?';
                        $query = $this->pdo->prepare($q);
                        return $query->execute(array($param1_v, $param2_v, $param3_v, $param4_v, $param5_v, $param6_v, $param7_v, $param8_v, $param9_v, $param10_v, $where1_v, $where2_v));
                        break;
                    case 3:
                        $q = $q . $where1_n . ' = ? and ' . $where2_n . ' = ? and ' . $where3_n . ' = ?';
                        $query = $this->pdo->prepare($q);
                        return $query->execute(array($param1_v, $param2_v, $param3_v, $param4_v, $param5_v, $param6_v, $param7_v, $param8_v, $param9_v, $param10_v, $where1_v, $where2_v, $where3_v));
                        break;
                    case 4:
                        $q = $q . $where1_n . ' = ? and ' . $where2_n . ' = ? and ' . $where3_n . ' = ? and ' . $where4_n . ' = ?';
                        $query = $this->pdo->prepare($q);
                        return $query->execute(array($param1_v, $param2_v, $param3_v, $param4_v, $param5_v, $param6_v, $param7_v, $param8_v, $param9_v, $param10_v, $where1_v, $where2_v, $where3_v, $where4_v));
                        break;
                    case 5:
                        $q = $q . $where1_n . ' = ? and ' . $where2_n . ' = ? and ' . $where3_n . ' = ? and ' . $where4_n . ' = ? and ' . $where5_n . ' = ?';
                        $query = $this->pdo->prepare($q);
                        return $query->execute(array($param1_v, $param2_v, $param3_v, $param4_v, $param5_v, $param6_v, $param7_v, $param8_v, $param9_v, $param10_v, $where1_v, $where2_v, $where3_v, $where4_v, $where5_v));
                        break;
                    default:
                        return;
                }
                break;
        }
    }

    /*
     * An example
      $args = array("display_name" => $name,
      "user_pass" => md5($this->pass),
      "user_email" => $user,
      "user_registered" => $mysqldate );
      $insert = $query->insert("users", $args);
     * 
     */

    public function insert($table, $fields) {

        if (is_array($fields))
            $size = self::fillParams($fields);

        $q = 'INSERT INTO ' . $table . ' ';
        switch ($size) {
            case 1:
                $q = $q . '(' . $this->paramsName[0] . ') VALUES( ? )';
                break;
            case 2:
                $q = $q . '(' . $this->paramsName[0] . ',' . $this->paramsName[1] . ') VALUES( ?, ?)';
                break;
            case 3:
                $q = $q . '(' . $this->paramsName[0] . ',' . $this->paramsName[1] . ',' . $this->paramsName[2] . ') VALUES( ?, ?, ?)';
                break;
            case 4:
                $q = $q . '(' . $this->paramsName[0] . ',' . $this->paramsName[1] . ',' . $this->paramsName[2] . ',' . $this->paramsName[3] . ') VALUES( ?, ?, ?, ?)';
                break;
            case 5:
                $q = $q . '(' . $this->paramsName[0] . ',' . $this->paramsName[1] . ',' . $this->paramsName[2] . ',' . $this->paramsName[3] . ',' . $this->paramsName[4] . ') VALUES( ?, ?, ?, ?, ?)';
                break;
            case 6:
                $q = $q . '(' . $this->paramsName[0] . ',' . $this->paramsName[1] . ',' . $this->paramsName[2] . ',' . $this->paramsName[3] . ',' . $this->paramsName[4] . ',' . $this->paramsName[5] . ') VALUES( ?, ?, ?, ?, ?, ?)';
                break;
            case 7:
                $q = $q . '(' . $this->paramsName[0] . ',' . $this->paramsName[1] . ',' . $this->paramsName[2] . ',' . $this->paramsName[3] . ',' . $this->paramsName[4] . ',' . $this->paramsName[5] . ',' . $this->paramsName[6] . ') VALUES( ?, ?, ?, ?, ?, ?, ?)';
                break;
            case 8:
                $q = $q . '(' . $this->paramsName[0] . ',' . $this->paramsName[1] . ',' . $this->paramsName[2] . ',' . $this->paramsName[3] . ',' . $this->paramsName[4] . ',' . $this->paramsName[5] . ',' . $this->paramsName[6] . ',' . $this->paramsName[7] . ') VALUES( ?, ?, ?, ?, ?, ?, ?, ?)';
                break;
            case 9:
                $q = $q . '(' . $this->paramsName[0] . ',' . $this->paramsName[1] . ',' . $this->paramsName[2] . ',' . $this->paramsName[3] . ',' . $this->paramsName[4] . ',' . $this->paramsName[5] . ',' . $this->paramsName[6] . ',' . $this->paramsName[7] . ',' . $this->paramsName[8] . ') VALUES( ?, ?, ?, ?, ?, ?, ?, ?, ?)';
                break;
            case 10:
                $q = $q . '(' . $this->paramsName[0] . ',' . $this->paramsName[1] . ',' . $this->paramsName[2] . ',' . $this->paramsName[3] . ',' . $this->paramsName[4] . ',' . $this->paramsName[5] . ',' . $this->paramsName[6] . ',' . $this->paramsName[7] . ',' . $this->paramsName[8] . ',' . $this->paramsName[9] . ') VALUES( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
                break;
            case 11:
                $q = $q . '(' . $this->paramsName[0] . ',' . $this->paramsName[1] . ',' . $this->paramsName[2] . ',' . $this->paramsName[3] . ',' . $this->paramsName[4] . ',' . $this->paramsName[5] . ',' . $this->paramsName[6] . ',' . $this->paramsName[7] . ',' . $this->paramsName[8] . ',' . $this->paramsName[9] . ',' . $this->paramsName[10] . ') VALUES( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
                break;
            case 12:
                $q = $q . '(' . $this->paramsName[0] . ',' . $this->paramsName[1] . ',' . $this->paramsName[2] . ',' . $this->paramsName[3] . ',' . $this->paramsName[4] . ',' . $this->paramsName[5] . ',' . $this->paramsName[6] . ',' . $this->paramsName[7] . ',' . $this->paramsName[8] . ',' . $this->paramsName[9] . ',' . $this->paramsName[10] . $this->paramsName[11] . ') VALUES( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
                break;
            case 13:
                $q = $q . '(' . $this->paramsName[0] . ',' . $this->paramsName[1] . ',' . $this->paramsName[2] . ',' . $this->paramsName[3] . ',' . $this->paramsName[4] . ',' . $this->paramsName[5] . ',' . $this->paramsName[6] . ',' . $this->paramsName[7] . ',' . $this->paramsName[8] . ',' . $this->paramsName[9] . ',' . $this->paramsName[10] . $this->paramsName[11] . $this->paramsName[12] . ') VALUES( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
                break;
            case 14:
                $q = $q . '(' . $this->paramsName[0] . ',' . $this->paramsName[1] . ',' . $this->paramsName[2] . ',' . $this->paramsName[3] . ',' . $this->paramsName[4] . ',' . $this->paramsName[5] . ',' . $this->paramsName[6] . ',' . $this->paramsName[7] . ',' . $this->paramsName[8] . ',' . $this->paramsName[9] . ',' . $this->paramsName[10] . $this->paramsName[11] . $this->paramsName[12] . $this->paramsName[13] . ') VALUES( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
                break;
            case 15:
                $q = $q . '(' . $this->paramsName[0] . ',' . $this->paramsName[1] . ',' . $this->paramsName[2] . ',' . $this->paramsName[3] . ',' . $this->paramsName[4] . ',' . $this->paramsName[5] . ',' . $this->paramsName[6] . ',' . $this->paramsName[7] . ',' . $this->paramsName[8] . ',' . $this->paramsName[9] . ',' . $this->paramsName[10] . $this->paramsName[11] . $this->paramsName[12] . $this->paramsName[13] . $this->paramsName[14] . ') VALUES( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
                break;
            case 16:
                $q = $q . '(' . $this->paramsName[0] . ',' . $this->paramsName[1] . ',' . $this->paramsName[2] . ',' . $this->paramsName[3] . ',' . $this->paramsName[4] . ',' . $this->paramsName[5] . ',' . $this->paramsName[6] . ',' . $this->paramsName[7] . ',' . $this->paramsName[8] . ',' . $this->paramsName[9] . ',' . $this->paramsName[10] . $this->paramsName[11] . $this->paramsName[12] . $this->paramsName[13] . $this->paramsName[14] . $this->paramsName[15] . ') VALUES( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
                break;
            case 17:
                $q = $q . '(' . $this->paramsName[0] . ',' . $this->paramsName[1] . ',' . $this->paramsName[2] . ',' . $this->paramsName[3] . ',' . $this->paramsName[4] . ',' . $this->paramsName[5] . ',' . $this->paramsName[6] . ',' . $this->paramsName[7] . ',' . $this->paramsName[8] . ',' . $this->paramsName[9] . ',' . $this->paramsName[10] . $this->paramsName[11] . $this->paramsName[12] . $this->paramsName[13] . $this->paramsName[14] . $this->paramsName[15] . $this->paramsName[16] . ') VALUES( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
                break;
            case 18:
                $q = $q . '(' . $this->paramsName[0] . ',' . $this->paramsName[1] . ',' . $this->paramsName[2] . ',' . $this->paramsName[3] . ',' . $this->paramsName[4] . ',' . $this->paramsName[5] . ',' . $this->paramsName[6] . ',' . $this->paramsName[7] . ',' . $this->paramsName[8] . ',' . $this->paramsName[9] . ',' . $this->paramsName[10] . $this->paramsName[11] . $this->paramsName[12] . $this->paramsName[13] . $this->paramsName[14] . $this->paramsName[15] . $this->paramsName[16] . $this->paramsName[17] . ') VALUES( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
                break;
            case 19:
                $q = $q . '(' . $this->paramsName[0] . ',' . $this->paramsName[1] . ',' . $this->paramsName[2] . ',' . $this->paramsName[3] . ',' . $this->paramsName[4] . ',' . $this->paramsName[5] . ',' . $this->paramsName[6] . ',' . $this->paramsName[7] . ',' . $this->paramsName[8] . ',' . $this->paramsName[9] . ',' . $this->paramsName[10] . $this->paramsName[11] . $this->paramsName[12] . $this->paramsName[13] . $this->paramsName[14] . $this->paramsName[15] . $this->paramsName[16] . $this->paramsName[17] . $this->paramsName[18] . ') VALUES( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
                break;
            case 20:
                $q = $q . '(' . $this->paramsName[0] . ',' . $this->paramsName[1] . ',' . $this->paramsName[2] . ',' . $this->paramsName[3] . ',' . $this->paramsName[4] . ',' . $this->paramsName[5] . ',' . $this->paramsName[6] . ',' . $this->paramsName[7] . ',' . $this->paramsName[8] . ',' . $this->paramsName[9] . ',' . $this->paramsName[10] . $this->paramsName[11] . $this->paramsName[12] . $this->paramsName[13] . $this->paramsName[14] . $this->paramsName[15] . $this->paramsName[16] . $this->paramsName[17] . $this->paramsName[18] . $this->paramsName[19] . ') VALUES( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
                break;
        }

        $query = $this->pdo->prepare($q);
        $query->execute($this->paramsValue);
        $this->paramsName = array();
        $this->paramsValue = array();
        return $this->pdo->lastInsertId();
    }

    public function truncate($table) {
        $q = 'TRUNCATE TABLE ' . $table;
        $query = $this->pdo->prepare($q);
        return $query->execute();
    }

}

?>
