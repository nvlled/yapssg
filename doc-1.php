
<?php
require_once('lib/yapssg.php');

renderPost([
    'id' => 1,
    'title' => 'Introduction',
    'date' => 1563248812,
    'description' => '',
    'tags' => ['hello', 'intro'],

    'category' => getCategoryByFilename(__FILE__),
]);
