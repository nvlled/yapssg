
<?php
require_once('lib/yapssg.php');

$post = [
    'id' => 3,
    'title' => 'Extending and modifying',
    'date' => 1563251754,
    'description' => '',
    'category' => getCategoryByFilename(__FILE__),
];
renderPost($post);
