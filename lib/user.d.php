<?php if (!defined("ZLH_INIT")) die;

/*
 * Auth class
 * Salt, hash and password
 */
class auth {
  public static function get_salt($length = 16) {
    $random = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $result = "";
    for ($i = 0; $i < $length; $i++) {
      $result .= $random[rand(0, strlen($random) - 1)];
    }
    return $result;
  }

  public static function get_hash($source, $salt = "", $times = 0) {
    $salt = $salt !== "" ? $salt : self::get_salt();
    $times = $times ? $times : rand(0, 99);
    $times += 100;
    $md5 = md5($source . $salt);
    for ($i = 0; $i < $times; $i++) {
      $md5 = md5($md5 . $salt);
    }
    $hash = $salt . '$' . $times . '$' . $md5;
    return $hash;
  }

  public static function compare($correct, $input) {
    if (!$input) return false;
    $parts = explode("$", $correct);
    $salt = $parts[0];
    $times = $parts[1];
    $hash = self::get_hash($input, $salt, $times - 100);
    echo $hash;
    return $correct == $hash;
  }

  public static function client_pass($uname, $hash) {
    $_SESSION["auth_name"] = $uname;
    $_SESSION["auth_pass"] = $hash;
    $_SESSION["auth_token"] = md5($hash . $_SERVER["REMOTE_ADDR"]);
  }

  public static function client_auth() {
    if (!isset($_SESSION["auth_pass"])) return false;
    return $_SESSION["auth_token"] == md5($_SESSION["auth_pass"] . $_SERVER["REMOTE_ADDR"]);
  }
}

/*
 * User layer
 * Login auth, client auth, info get & set
 */
class user {
  /*public $sql;

  public function __construct() {
    global $sql;
    $this->sql = $sql;
  }*/

  public static function get_hash($uname) {
    global $sql;
    if (!$sql) new err("Sql not initialized", "user.d");
    if ($uname == "") new err("Username not valid", "user.d");
    sql::make($uname);
    $res = $sql->select([
      "select" => "hash",
      "from" => "hanauser",
      "where" => "`uname` = '$uname'",
      "limit" => 1
    ]);
    $a = $res->fetch_assoc();
    $res->free();
    return $a["hash"] ? $a["hash"] : false;
  }

  public static function login($uname, $password) {
    $correct = self::get_hash($uname);
    if (auth::compare($correct, $password)) {
      auth::client_pass($uname ,$correct);
      return true;
    } else return false;
  }

  public static function auth() {
    return auth::client_auth();
  }

  public static function get_info($uname) {
    global $sql;
    if (!$sql) new err("Sql not initialized", "user.d");
    if ($uname == "") new err("Username not valid", "user.d");
    sql::make($uname);
    $res = $sql->select([
      "select" => "*",
      "from" => "hanauser",
      "where" => "`uname` = '$uname'"
    ]);
    $a = $res->fetch_assoc();
    $res->free();
    return $a;
  }

  public static function update_info($uname, $info) {
    global $sql;
    if (!$sql) new err("Sql not initialized", "user.d");
    if ($uname == "") new err("Username not valid", "user.d");
    if ($name || $desc !== "false") {
      $a = [];
      foreach ($info as $key => $val) {
        switch ($key) {
          case "name":
          case "desc":
          case "mail":
          $a[$key] = $val;
        }
      }
      if (empty($a)) return false;
      sql::make($a);
      sql::make($uname);
      return $sql->update([
        "update" => "hanauser",
        "set" => $a,
        "where" => "`uname` = '$uname'"
      ]);
    }
  }

  public static function update_pass($uname, $newpass) {
    global $sql;
    if (!$sql) new err("Sql not initialized", "user.d");
    if ($uname == "") new err("Username not valid", "user.d");
    if ($newpass == "") new err("New password not set", "user.d");
    sql::make([&$uname, &$newpass]);
    return $sql->update([
      "update" => "hanauser",
      "set" => ["hash" => auth::get_hash($newpass)],
      "where" => "`uname` = '$uname'"
    ]);
  }

  public static function get_avatar($uname, $size = 80, $rating = "g") {
    global $sql;
    if (!$sql) new err("Sql not initialized", "user.d");
    if ($uname == "") new err("Username not valid", "user.d");
    sql::make($uname);
    $res = $sql->select([
      "select" => "mail",
      "from" => "hanauser",
      "where" => "`uname` = '$uname'"
    ]);
    $a = $res->fetch_assoc();
    $res->free();
    if (isset($a["mail"]) && $a["mail"] !== "") {
      $prefix = "//cn.gravatar.com/avatar/";
      $md5 = md5($a["mail"]);
      $url = "$prefix$md5?s=$size&r=$rating";
      return $url;
    }
    return false;
  }

  public static function count_koto($uname) {
    global $sql;
    if (!$sql) new err("Sql not initialized", "user.d");
    if ($uname == "") new err("Username not valid", "user.d");
    sql::make($uname);
    $res = $sql->select([
      "select" => "index",
      "from" => "hanakoto",
      "where" => "`author` = '$uname'"
    ]);
    if ($res) {
      $a = $res->num_rows;
      $res->free();
      return $a;
    }
    return false;
  }

  public static function last_seen($uname) {
    global $sql;
    if (!$sql) new err("Sql not initialized", "user.d");
    if ($uname == "") new err("Username not valid", "user.d");
    sql::make($uname);
    $res = $sql->select([
      "select" => "datetime",
      "from" => "hanakoto",
      "where" => "`author` = '$uname'",
      "orderby" => "`datetime` DESC",
      "limit" => 1
    ]);
    if ($res) {
      $a = $res->fetch_assoc();
      return $a["datetime"];
    }
    return false;
  }
}

?>
