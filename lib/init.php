<?php

define("ZLH_INIT", 1);
$dbconfig = [
  "host" => "localhost",
  "username" => "root",
  "password" => "kawaii",
  "database" => "hanakoto"
];

require_once("sql.inc.php");
$sql = new sql($dbconfig);

?>
