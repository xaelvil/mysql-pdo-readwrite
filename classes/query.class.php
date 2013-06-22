<?php

/**
 * Class for querys the database
 *
 * @author xaelvil
 */
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

    public function __construct() {
        $db = new db();
        $this->host = $db->getHost();
        $this->user = $db->getUser();
        $this->pass = $db->getPass();
        $this->databaseName = $db->getDatabaseName();
        $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->databaseName . ";charset=utf8";
        $this->pdo = new PDO($dsn, $this->user, $this->pass, $this->opt);
    }

    /* public function authToNode($nodeId) {

      $customer = $_SESSION['customer_id'];
      $args1 = array("customer_id" => $customer);
      $args2 = array("id" => $nodeId);

      $result = self::join2Query('chart_company', 'node', 'a.chart_id = b.chart_id', $args1, $args2, 'chart_id', '');

      if ($result) {
      return true;
      } else {
      return false;
      }
      } */

    /* public function authToChart($chartId) {
      $customer = $_SESSION['customer_id'];
      $args = array('customer_id' => $customer);
      $args = array('chart_id' => $chartId) + $args;
      $result = self::easyQuery("chart_company", $args, "*");

      if ($result) {
      return true;
      } else {
      return false;
      }
      } */

    public function getCount($table) {

        $q = 'select count(*) from ' . $table;
        $query = $this->pdo->prepare($q);
        $query->execute();
        $return = $query->fetchAll();
        if (is_array($return) && isset($return[0]['count(*)']))
            return $return[0]['count(*)'];
    }

    public function prepareResult($result, $out = true) {
        if ($out) {
            return '{"records":' . json_encode($result) . '}';
        } else {
            return $result;
        }
    }

    public function easyQuery($table_name, $params, $get) {

        $q = 'SELECT ' . $get . ' from ' . $table_name;

        if (is_array($params)) {
            $size = sizeof($params);
            $i = 1;
            foreach ($params as $key => $value) {
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
                }
                $i++;
            }
            unset($key);
            unset($value);
            unset($i);
        } else {
            $size = 0;
        }
        switch ($size) {
            case 1:
                $q = $q . ' where ' . $param1_n . ' = ?';
                $query = $this->pdo->prepare($q);
                $query->execute(array($param1_v));
                break;
            case 2:
                $q = $q . ' where ' . $param1_n . ' = ? and ' . $param2_n . ' = ?';
                $query = $this->pdo->prepare($q);
                $query->execute(array($param1_v, $param2_v));
                break;
            case 3:
                $q = $q . ' where ' . $param1_n . ' = ? and ' . $param2_n . ' = ? and '
                        . $param3_n . ' = ?';
                $query = $this->pdo->prepare($q);
                $query->execute(array($param1_v, $param2_v, $param3_v));
                break;
            case 4:
                $q = $q . ' where ' . $param1_n . ' = ? and ' . $param2_n . ' = ? and '
                        . $param3_n . ' = ? and ' . $param4_n . ' = ?';
                $query = $this->pdo->prepare($q);
                $query->execute(array($param1_v, $param2_v, $param3_v, $param4_v));
                break;
            case 5:
                $q = $q . ' where ' . $param1_n . ' = ? and ' . $param2_n . ' = ? and '
                        . $param3_n . ' = ? and ' . $param4_n . ' = ? and ' . $param5_n . ' = ?';
                $query = $this->pdo->prepare($q);
                $query->execute(array($param1_v, $param2_v, $param3_v, $param4_v, $param5_v));
                break;
            default:
                $query = $this->pdo->prepare($q);
                $query->execute();
                break;
        }

        return $query->fetchAll();
    }

    /*
     * 
      $args1 = array("customer_id" => $customer);
      $args2 = array("id" => $nodeId);
      $result = $query->join2Query('company', 'node', 'a.chart_id = b.chart_id', $args1, $args2, 'id', 'name');
     * 
     */
    public function join2Query($table1_name, $table2_name, $cond, $params1, $params2, $get1, $get2) {
        //select join, no * allowed
        //$q query
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
            $sizeWhere = sizeof($fields);
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
                    case 11:
                        $param11_n = $key;
                        $param11_v = $value;
                        break;

                    case 12:
                        $param12_n = $key;
                        $param12_v = $value;
                        break;
                    case 13:
                        $param13_n = $key;
                        $param13_v = $value;
                        break;
                    case 14:
                        $param14_n = $key;
                        $param14_v = $value;
                        break;
                    case 15:
                        $param15_n = $key;
                        $param15_v = $value;
                        break;
                    case 16:
                        $param16_n = $key;
                        $param16_v = $value;
                        break;
                    case 17:
                        $param17_n = $key;
                        $param17_v = $value;
                        break;
                    case 18:
                        $param18_n = $key;
                        $param18_v = $value;
                        break;
                    case 19:
                        $param19_n = $key;
                        $param19_v = $value;
                        break;
                    case 20:
                        $param20_n = $key;
                        $param20_v = $value;
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

        $q = 'INSERT INTO ' . $table;
        switch ($size) {
            case 1:
                $q = $q . '(' . $param1_n . ') VALUES( ? )';
                $query = $this->pdo->prepare($q);
                $query->execute(array($param1_v));
                break;
            case 2:
                $q = $q . '(' . $param1_n . ',' . $param2_n . ') VALUES( ?, ?)';
                $query = $this->pdo->prepare($q);
                $query->execute(array($param1_v, $param2_v));
                break;
            case 3:
                $q = $q . '(' . $param1_n . ',' . $param2_n . ',' . $param3_n . ') VALUES( ?, ?, ?)';
                $query = $this->pdo->prepare($q);
                $query->execute(array($param1_v, $param2_v, $param3_v));
                break;
            case 4:
                $q = $q . '(' . $param1_n . ',' . $param2_n . ',' . $param3_n . ',' . $param4_n . ') VALUES( ?, ?, ?, ?)';
                $query = $this->pdo->prepare($q);
                $query->execute(array($param1_v, $param2_v, $param3_v, $param4_v));
                break;
            case 5:
                $q = $q . '(' . $param1_n . ',' . $param2_n . ',' . $param3_n . ',' . $param4_n . ',' . $param5_n . ') VALUES( ?, ?, ?, ?, ?)';
                $query = $this->pdo->prepare($q);
                $query->execute(array($param1_v, $param2_v, $param3_v, $param4_v, $param5_v));
                break;
            case 6:
                $q = $q . '(' . $param1_n . ',' . $param2_n . ',' . $param3_n . ',' . $param4_n . ',' . $param5_n . ',' . $param6_n . ') VALUES( ?, ?, ?, ?, ?, ?)';
                $query = $this->pdo->prepare($q);
                $query->execute(array($param1_v, $param2_v, $param3_v, $param4_v, $param5_v, $param6_v));
                break;
            case 7:
                $q = $q . '(' . $param1_n . ',' . $param2_n . ',' . $param3_n . ',' . $param4_n . ',' . $param5_n . ',' . $param6_n . ',' . $param7_n . ') VALUES( ?, ?, ?, ?, ?, ?, ?)';
                $query = $this->pdo->prepare($q);
                $query->execute(array($param1_v, $param2_v, $param3_v, $param4_v, $param5_v, $param6_v, $param7_v));
                break;
            case 8:
                $q = $q . '(' . $param1_n . ',' . $param2_n . ',' . $param3_n . ',' . $param4_n . ',' . $param5_n . ',' . $param6_n . ',' . $param7_n . ',' . $param8_n . ') VALUES( ?, ?, ?, ?, ?, ?, ?, ?)';
                $query = $this->pdo->prepare($q);
                $query->execute(array($param1_v, $param2_v, $param3_v, $param4_v, $param5_v, $param6_v, $param7_v, $param8_v));
                break;
            case 9:
                $q = $q . '(' . $param1_n . ',' . $param2_n . ',' . $param3_n . ',' . $param4_n . ',' . $param5_n . ',' . $param6_n . ',' . $param7_n . ',' . $param8_n . ',' . $param9_n . ') VALUES( ?, ?, ?, ?, ?, ?, ?, ?, ?)';
                $query = $this->pdo->prepare($q);
                $query->execute(array($param1_v, $param2_v, $param3_v, $param4_v, $param5_v, $param6_v, $param7_v, $param8_v, $param9_v));
                break;
            case 10:
                $q = $q . '(' . $param1_n . ',' . $param2_n . ',' . $param3_n . ',' . $param4_n . ',' . $param5_n . ',' . $param6_n . ',' . $param7_n . ',' . $param8_n . ',' . $param9_n . ',' . $param10_n . ') VALUES( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
                $query = $this->pdo->prepare($q);
                $query->execute(array($param1_v, $param2_v, $param3_v, $param4_v, $param5_v, $param6_v, $param7_v, $param8_v, $param9_v, $param10_v));
                break;
            case 11:
                $q = $q . '(' . $param1_n . ',' . $param2_n . ',' . $param3_n . ',' . $param4_n . ',' . $param5_n . ',' . $param6_n . ',' . $param7_n . ',' . $param8_n . ',' . $param9_n . ',' . $param10_n . ',' . $param11_n . ') VALUES( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
                $query = $this->pdo->prepare($q);
                $query->execute(array($param1_v, $param2_v, $param3_v, $param4_v, $param5_v, $param6_v, $param7_v, $param8_v, $param9_v, $param10_v, $param11_v));
                break;
            case 12:
                $q = $q . '(' . $param1_n . ',' . $param2_n . ',' . $param3_n . ',' . $param4_n . ',' . $param5_n . ',' . $param6_n . ',' . $param7_n . ',' . $param8_n . ',' . $param9_n . ',' . $param10_n . ',' . $param11_n . ',' . $param12_n . ') VALUES( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
                $query = $this->pdo->prepare($q);
                $query->execute(array($param1_v, $param2_v, $param3_v, $param4_v, $param5_v, $param6_v, $param7_v, $param8_v, $param9_v, $param10_v, $param11_v, $param12_v));
                break;
            case 13:
                $q = $q . '(' . $param1_n . ',' . $param2_n . ',' . $param3_n . ',' . $param4_n . ',' . $param5_n . ',' . $param6_n . ',' . $param7_n . ',' . $param8_n . ',' . $param9_n . ',' . $param10_n . ',' . $param11_n . ',' . $param12_n . ',' . $param13_n . ') VALUES( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
                $query = $this->pdo->prepare($q);
                $query->execute(array($param1_v, $param2_v, $param3_v, $param4_v, $param5_v, $param6_v, $param7_v, $param8_v, $param9_v, $param10_v, $param11_v, $param12_v, $param13_v));
                break;
            case 14:
                $q = $q . '(' . $param1_n . ',' . $param2_n . ',' . $param3_n . ',' . $param4_n . ',' . $param5_n . ',' . $param6_n . ',' . $param7_n . ',' . $param8_n . ',' . $param9_n . ',' . $param10_n . ',' . $param11_n . ',' . $param12_n . ',' . $param13_n . ',' . $param14_n . ') VALUES( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
                $query = $this->pdo->prepare($q);
                $query->execute(array($param1_v, $param2_v, $param3_v, $param4_v, $param5_v, $param6_v, $param7_v, $param8_v, $param9_v, $param10_v, $param11_v, $param12_v, $param13_v, $param14_v));
                break;
            case 15:
                $q = $q . '(' . $param1_n . ',' . $param2_n . ',' . $param3_n . ',' . $param4_n . ',' . $param5_n . ',' . $param6_n . ',' . $param7_n . ',' . $param8_n . ',' . $param9_n . ',' . $param10_n . ',' . $param11_n . ',' . $param12_n . ',' . $param13_n . ',' . $param14_n . ',' . $param15_n . ') VALUES( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
                $query = $this->pdo->prepare($q);
                $query->execute(array($param1_v, $param2_v, $param3_v, $param4_v, $param5_v, $param6_v, $param7_v, $param8_v, $param9_v, $param10_v, $param11_v, $param12_v, $param13_v, $param14_v, $param15_v));
                break;
            case 16:
                $q = $q . '(' . $param1_n . ',' . $param2_n . ',' . $param3_n . ',' . $param4_n . ',' . $param5_n . ',' . $param6_n . ',' . $param7_n . ',' . $param8_n . ',' . $param9_n . ',' . $param10_n . ',' . $param11_n . ',' . $param12_n . ',' . $param13_n . ',' . $param14_n . ',' . $param15_n . ',' . $param16_n . ') VALUES( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
                $query = $this->pdo->prepare($q);
                $query->execute(array($param1_v, $param2_v, $param3_v, $param4_v, $param5_v, $param6_v, $param7_v, $param8_v, $param9_v, $param10_v, $param11_v, $param12_v, $param13_v, $param14_v, $param15_v, $param16_v));
                break;
            case 17:
                $q = $q . '(' . $param1_n . ',' . $param2_n . ',' . $param3_n . ',' . $param4_n . ',' . $param5_n . ',' . $param6_n . ',' . $param7_n . ',' . $param8_n . ',' . $param9_n . ',' . $param10_n . ',' . $param11_n . ',' . $param12_n . ',' . $param13_n . ',' . $param14_n . ',' . $param15_n . ',' . $param16_n . ',' . $param17_n . ') VALUES( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
                $query = $this->pdo->prepare($q);
                $query->execute(array($param1_v, $param2_v, $param3_v, $param4_v, $param5_v, $param6_v, $param7_v, $param8_v, $param9_v, $param10_v, $param11_v, $param12_v, $param13_v, $param14_v, $param15_v, $param16_v, $param17_v));
                break;
            case 18:
                $q = $q . '(' . $param1_n . ',' . $param2_n . ',' . $param3_n . ',' . $param4_n . ',' . $param5_n . ',' . $param6_n . ',' . $param7_n . ',' . $param8_n . ',' . $param9_n . ',' . $param10_n . ',' . $param11_n . ',' . $param12_n . ',' . $param13_n . ',' . $param14_n . ',' . $param15_n . ',' . $param16_n . ',' . $param17_n . ',' . $param18_n . ') VALUES( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
                $query = $this->pdo->prepare($q);
                $query->execute(array($param1_v, $param2_v, $param3_v, $param4_v, $param5_v, $param6_v, $param7_v, $param8_v, $param9_v, $param10_v, $param11_v, $param12_v, $param13_v, $param14_v, $param15_v, $param16_v, $param17_v, $param18_v));
                break;
            case 19:
                $q = $q . '(' . $param1_n . ',' . $param2_n . ',' . $param3_n . ',' . $param4_n . ',' . $param5_n . ',' . $param6_n . ',' . $param7_n . ',' . $param8_n . ',' . $param9_n . ',' . $param10_n . ',' . $param11_n . ',' . $param12_n . ',' . $param13_n . ',' . $param14_n . ',' . $param15_n . ',' . $param16_n . ',' . $param17_n . ',' . $param18_n . ',' . $param19_n . ') VALUES( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
                $query = $this->pdo->prepare($q);
                $query->execute(array($param1_v, $param2_v, $param3_v, $param4_v, $param5_v, $param6_v, $param7_v, $param8_v, $param9_v, $param10_v, $param11_v, $param12_v, $param13_v, $param14_v, $param15_v, $param16_v, $param17_v, $param18_v, $param19_v));
                break;
            case 20:
                $q = $q . '(' . $param1_n . ',' . $param2_n . ',' . $param3_n . ',' . $param4_n . ',' . $param5_n . ',' . $param6_n . ',' . $param7_n . ',' . $param8_n . ',' . $param9_n . ',' . $param10_n . ',' . $param11_n . ',' . $param12_n . ',' . $param13_n . ',' . $param14_n . ',' . $param15_n . ',' . $param16_n . ',' . $param17_n . ',' . $param18_n . ',' . $param19_n . ',' . $param20_n . ') VALUES( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
                $query = $this->pdo->prepare($q);
                $query->execute(array($param1_v, $param2_v, $param3_v, $param4_v, $param5_v, $param6_v, $param7_v, $param8_v, $param9_v, $param10_v, $param11_v, $param12_v, $param13_v, $param14_v, $param15_v, $param16_v, $param17_v, $param18_v, $param19_v, $param20_v));
                break;
        }
        return $this->pdo->lastInsertId();
    }

    public function truncate($table) {
        $q = 'TRUNCATE TABLE ' . $table;
        $query = $this->pdo->prepare($q);
        return $query->execute();
    }

}

?>
