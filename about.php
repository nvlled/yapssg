<?php
require_once 'lib/yapssg.php';

render(['title' => 'About'], function () {
    ?>

<h3>About </h3>

<?=mdFile('about'); ?>

<?php
});
