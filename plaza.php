<?php

require_once('lib/init.php');
require_once('lib/data.d.php');
require_once('lib/plaza.d.php');
$p = isset($_GET["p"]) && $_GET["p"] > 0 ? $_GET["p"] : 1;
new plaza($p);

?>
