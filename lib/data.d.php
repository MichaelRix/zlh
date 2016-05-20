<?php if (!defined("ZLH_INIT")) die;

/*
 * Single data class
 */
class koto {
  public $author = "undefined";
  public $time = "ffffff";
  public $text = "";

  public function time($timestring) {
    $timestamp = strtotime($timestring);
    $md5 = md5($timestamp);
    $hash = substr($md5, 26);
    $this->time = $hash;
  }
}

/*
 * Single page class
 */
class pagedata {
  public $navarray = [];
  public $data = [];
  public $sql;

  public function count($current) {
    if (!$this->sql) new zerror("Sql not initialized", "data.d");
    $res = $this->sql->select([
      "select" => "index",
      "from" => "hanakoto",
      "where" => "1"
    ]);
    $number = $res->num_rows;
    $pn = $number / 30;
    $pn = ceil($pn);
    $res->free();
    $a = [];
    for ($i = 1; $i <= $pn; $i++) {
      array_push($a, $i);
    }
    if ($pn <= 5) {
      $this->navarray = array_slice($a, 0, $pn);
    } else if ($current < 3) {
      $this->navarray = array_slice($a, 0, 5);
    } else if ($pn - $current < 3) {
      $this->navarray = array_slice($a, $pn - 5, 5);
    } else {
      $this->navarray = array_slice($a, $current - 3, 5);
    }
  }
  public function fetch($pn) {
    if (!$this->sql) new zerror("Sql not initialized", "data.d");
    $offset = ($pn - 1) * 30;
    $res = $this->sql->select([
      "select" => "",
      "from" => "hanakoto",
      "where" => "1",
      "limit" => 30,
      "offset" => $offset
    ]);
    while ($one = $res->fetch_assoc()) {
      $temp = new koto();
      $temp->author = "花兒";
      $temp->text = $one["text"];
      $temp->time($one["datetime"]);
      array_push($this->data, $temp);
    }
    $res->free();
  }

  public function __construct($pn = 1) {
    global $sql;
    $this->sql = $sql;
    $this->count($pn);
    $this->fetch($pn);
  }
}

?>
