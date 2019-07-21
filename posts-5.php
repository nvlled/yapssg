
<?php
require_once 'lib/yapssg.php';

renderPost([
    'id' => 5,
    'title' => 'Added features',
    'date' => 1563695635,
    'description' => '',
    'draft' => false,
    'category' => getCategoryByFilename(__FILE__),
]);
