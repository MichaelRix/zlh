<?php if (!defined("ZLH_INIT")) die;

/*
 * Display of the plaza page
 */
class plaza_page {
  public $nava;
  public $data;

  public function __construct($pagedata) {
    if (!isset($pagedata->data)) die;
    $this->data = $pagedata->data;
    $this->nava = $pagedata->navarray;
  }

  public function show() {
    if (!$this->data) die;
    ?><!DOCTYPE HTML>
    <html>
      <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" >
        <link rel="stylesheet" href="inc/normalize.css" type="text/css">
        <link rel="stylesheet" href="inc/elem.basic.css" type="text/css">
        <link rel="stylesheet" href="inc/framework.css" type="text/css">
        <link rel="stylesheet" href="inc/classes.css" type="text/css">
        <title>√廣場 @ ZLH</title>
      </head>
      <body>
        <div id="nav">
          <div class="nav_first">
            <h1><a href="index.html">√ZLH物語</a></h1>
          </div>
          <div class="nav_item">
            <a class="current" href="plaza.php">廣場</a>
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
        <!-- begin --><?php foreach ($this->data as $one) {
  $author = $one->author;
  $text = $one->text;
  $time = $one->time; ?>

          <div class="single">
            <p class="text"><?php $text = str_ireplace("，", "，" . PHP_EOL, $text);
            $a = explode("\n", $text);
            foreach ($a as $span) { ?>

              <span><?php echo $span; ?></span><?php } ?>

            </p>
            <p class="info">
              <span class="author"><a href="#"><?php echo $author; ?></a></span>
              <span class="time"><a href="#"><?php echo $time; ?></a></span>
            </p>
          </div>
          <!-- end -->
        </div><?php } ?>

        <!-- main -->

        <div id="pages"><?php foreach ($this->nava as $b) { ?>

          <span><a<?php global $p; if ($p == $b) {?> class="current"<?php } ?> href="<?php echo "?p=" . $b; ?>"><?php echo $b; ?></a></span><?php } ?>

        </div>
        <!-- pages -->

        <div class="footer">
          <p>&copy; <a href="https://www.nottres.com">Nottres! Network!</a> 2016.</p>
          <p>Some rights reserved.</p>
        </div>
        <!-- footer -->
      </body>
    </html>
    <?php
  }
}

/*
 * Plaza class
 */
class plaza {
  public function __construct($pn = 1) {
    $pagedata = new pagedata($pn);
    $plaza_page = new plaza_page($pagedata);
    $plaza_page->show();
  }
}

?>
