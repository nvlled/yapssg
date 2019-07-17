
<?php
require_once('lib/yapssg.php');

renderPost([
    'id' => 1,
    'title' => 'Hello',
    'date' => 1563341842,
    'description' => '',
    'category' => getCategoryByFilename(__FILE__),
]);
