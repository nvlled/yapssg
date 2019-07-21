
<?php
require_once('lib/yapssg.php');

renderPost([
    'id' => 2,
    'title' => 'Raining',
    'date' => 1563341879,
    'description' => '',
    'category' => getCategoryByFilename(__FILE__),
]);
