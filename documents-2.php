
<?php
require_once('lib/yapssg.php');

$post = [
    'id' => 2,
    'title' => 'Getting started',
    'date' => 1563248956,
    'description' => '',
    'category' => getCategoryByFilename(__FILE__),
];
renderPost($post);
