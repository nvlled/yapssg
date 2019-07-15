#!/usr/bin/env php
<?php
chdir(dirname(dirname(__FILE__)));

function nextID() {
    $maxID = 0;
    foreach (glob("post-*.php") as $filename) {
        $n = preg_match("/post-([0-9]*)\.php/", $filename, $m);
        if ($n <= 0) {
            continue;
        }
        $id = intval($m[1]);
        if ($maxID < $id) {
            $maxID = $id;
        }
    };
    return $maxID + 1;
}


$id = getenv("id");
$title = addslashes(getenv("title"));
$description = addslashes(@getenv("description"));
$date = time();

if (!$title) {
    echo "set title in ENV\n";
    echo "optionally set description, and id\n";
    exit;
}

if (!$id) {
    $id = nextID();
} else if (preg_match("/[^0-9]/", $id)) {
    echo "id must be an +integer\n";
    exit;
}

$filename = "post-$id.php";

if (file_exists("$filename")) {
    echo "post id=$id is already used.\n";
    exit;
}

$contents = "
<?php
require_once('lib/index.php');

\$post = [
    'id' => $id,
    'title' => '$title',
    'date' => $date,
    'description' => '$description',
];
renderPost(\$post);
";

file_put_contents($filename, $contents);
file_put_contents("content/post-$id.md", "
# Hello

This is post $id.

Add more markdown content here.
");

echo "post created with id=$id\n";