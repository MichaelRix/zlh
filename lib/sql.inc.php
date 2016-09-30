<?php if (!defined("ZLH_INIT")) die;

/*
 * Temporary error class
 */
class zerror {
  public function __construct($message, $where = "Unknown") {
    die($message . " - " . $where);
  }
}

/*
 * MySQLi driver class
 * from Shuryohana! Project!
 */
 class sql {
   public $sql;
   public $db = "";
   public $charset = "";

   public function __construct($config) {
     $this->sql = new mysqli(
       isset($config["host"]) ? $config["host"] : "localhost",
       isset($config["username"]) ? $config["username"] : "root",
       isset($config["password"]) ? $config["password"] : "",
       isset($config["database"]) ? $config["database"] : ""
     );
     if ($this->sql->connect_errno) new zerror("Failed to connect via mysqli", "mysql.inc");
     if ($this->sql->set_charset("utf8")) $this->charset = "utf8";
     if ($config["database"]) $this->db = $config["database"];
   }

   public function __destruct() {
     $this->sql->close();
   }

   public function db($database) {
     if (!$this->sql || !$database) new zerror("Not connected to server yet", "mysql.inc");
     if ($this->sql->select_db($database)) $this->db = $database;
   }

   public function charset($charset) {
     if (!$this->sql || !$charset) new zerror("Not connected to server yet", "mysql.inc");
     if ($this->sql->set_charset($charset)) $this->charset = $charset;
   }

   public function ready() {
     return $this->sql && $this->db;
   }

   public static function make(&$thing) {
     if (!is_array($thing)) $thing = addslashes($thing);
     else {
        foreach ($thing as &$input) {
          $input = addslashes($input);
        }
      }
   }

   public function select($args) {
     if (!$this->ready()) new zerror("Unexpected selection", "mysql.inc");
     $select = isset($args["select"]) && $args["select"] !== "" && $args["select"] !== "*" ? "`" . $args["select"] . "`" : "*";
     $from = isset($args["from"]) && $args["from"] !== "" ? "`" . $args["from"] . "`" : new zerror("From table not given", "mysql.inc");
     $where = isset($args["where"]) ? $args["where"] : false;
     $orderby = isset($args["orderby"]) ? $args["orderby"] : false;
     $limit = isset($args["limit"]) && $args["limit"] !== "" ? $args["limit"] : false;
     $offset = isset($args["offset"]) && $args["offset"] !=="" ? $args["offset"] : false;

     $query = "SELECT $select FROM $from";
     if ($where) $query .= " WHERE $where";
     if ($orderby) $query .= " ORDER BY $orderby";
     if ($limit > 0) $query .= " LIMIT $limit";
     if ($offset > 0) $query .= " OFFSET $offset";

     return $this->sql->query($query);
   }

   public function insert($args) {
     if (!$this->ready()) new zerror("Unexpected insertion", "mysql.inc");
     $insert = isset($args["insert"]) && $args["insert"] !== "" ? $args["insert"] : new zerror("Target table not given", "mysql.inc");
     $columns = isset($args["columns"]) && !empty($args["columns"]) ? $args["columns"] : new zerror("Insert columns not given", "mysql.inc");
     $values = isset($args["values"]) && !empty($args["values"]) ? $args["values"] : new zerror("Values not given", "mysql.inc");

     $what = implode(",", $what);
     $query = "INSERT INTO $insert ($columns) VALUES";
     if (is_array($values[0])) {
       foreach ($values as $value) {
         $value = implode("', '", $value);
         $query .= " ('$value'),";
       }
       $query = rtrim($query, ",");
     } else {
       $values = implode("', '", $values);
       $query .= " ('$values')";
     }

     echo $query;
     return $this->sql->query($query);
   }

   public function update($args) {
     if (!$this->ready()) new zerror("Unexpected update", "mysql.inc");
     $update = isset($args["update"]) && $args["update"] !== "" ? "`" . $args["update"] . "`" : new zerror("Target table not given", "mysql.inc");
     $set = isset($args["set"]) && !empty($args["set"]) ? $args["set"] : new zerror("Update values not given", "mysql.inc");
     $where = isset($args["where"]) && $args["where"] !== "" ? $args["where"] : "1";
     $orderby = isset($args["orderby"]) && $args["orderby"] !== "" ? $args["orderby"] : false;
     $limit = isset($args["limit"]) && $args["limit"] !== "" ? $args["limit"] : false;

     $query = "UPDATE $update SET";
     foreach($set as $key => $val) {
       $key = "`$key`";
       $val = "'$val'";
       $query .= " $key=$val,";
     }
     $query = rtrim($query, ",");
     $query .= " WHERE $where";
     if ($orderby) $query .= " ORDER BY $orderby";
     if ($limit) $query .= " LIMIT $limit";

     return $this->sql->query($query);
   }

   public function delete($args) {
     if (!$this->ready()) new zerror("Unexpected deletion", "mysql.inc");
     $from = isset($args["from"]) && $args["from"] !== "" ? "`" . $args["from"] . "`" : new zerror("Target table not given", "mysql.inc");
     $where = isset($args["where"]) && $args["where"] !== "" ? $args["where"] : "1";
     $orderby = isset($args["orderby"]) && $args["orderby"] !== "" ? $args["orderby"] : false;
     $limit = isset($args["limit"]) && $args["limit"] !== "" ? $args["limit"] : false;

     $query = "DELETE FROM $from WHERE $where";
     if ($orderby) $query .= " ORDER BY $orderby";
     if ($limit) $query .= " LIMIT $limit";

     return $this->sql->query($query);
   }
 }

?>
