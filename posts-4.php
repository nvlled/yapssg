
<?php
require_once 'lib/yapssg.php';

renderPost([
    'id' => 4,
    'title' => 'Planned features',
    'date' => 1563536329,
    'description' => '',
    'draft' => false,
    'category' => getCategoryByFilename(__FILE__),
]);
