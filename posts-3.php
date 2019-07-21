
<?php
require_once('lib/yapssg.php');

renderPost([
    'id' => 3,
    'title' => 'Log',
    'date' => 1563421264,
    'description' => '',
    'category' => getCategoryByFilename(__FILE__),
]);
