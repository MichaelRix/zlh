<?php
require_once('lib/init.php');
require_once('lib/user.d.php');
$name = user::get_info("Michael")["name"];
$avatar = user::get_avatar("Michael", 200);
$count = user::count_koto("Michael");
$last = date("Y/m/d H:i", strtotime(user::last_seen("Michael")));
?><!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
<link rel="stylesheet" href="inc/normalize.css" type="text/css">
<link rel="stylesheet" href="inc/elem.basic.css" type="text/css">
<link rel="stylesheet" href="inc/framework.css" type="text/css">
<link rel="stylesheet" href="inc/classes.css" type="text/css">
<title>關於我 - <?php echo $name; ?></title>
</head>
<body>
<div id="nav">
	<div class="nav_first">
		<h1><a href="index.html">√ZLH物語</a></h1>
	</div>
	<div class="nav_item">
		<a href="plaza.php">廣場</a>
	</div>
	<div class="nav_item">
		<a href="discover.php">發現</a>
	</div>
	<div class="nav_item">
		<a href="this.php">我</a>
	</div>
</div>
<!-- nav -->

<div id="main">
	<div class="single">
		<p class="text" style="text-align: center;">
			<img src="<?php echo $avatar; ?>">
			<span><?php echo $name; ?></span>
			<span>所著 <?php echo $count; ?></span>
		</p>
		<p class="info">
			<span class="author">
        <a href="#"><?php echo $name; ?></a>
      </span>
      <span class="time">
        <a href="#"><?php echo $last; ?></a>
      </span>
		</p>
	</div>
</div>
<!-- main -->

<div class="footer">
	<p>
		&copy; <a href="https://www.nottres.com">Nottres! Network!</a> 2016.
	</p>
	<p>
		Some rights reserved.
	</p>
	<p>
		<a href="about.html"># 關於…… #</a>
	</p>
</div>
<!-- footer -->
</body>
</html>
