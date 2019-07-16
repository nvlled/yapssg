<?php
require_once("lib/yapssg.php");

render(["title" => "About"], function() { ?>

<h3>About </h3>

<?=md("
This is an about page. You can use markdown here,
or just plain html markup, whichever is more convenient.
")?>

<?php });
