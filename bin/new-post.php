#!/usr/bin/env php
<?php
chdir(dirname(dirname(__FILE__)));
require_once ("lib/slug.php");

function nextID($category="post") {
    $maxID = 0;
    foreach (glob("$category-*.php") as $filename) {
        $n = preg_match("/$category-([0-9]*).*\.php/", $filename, $m);
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

function postExists($id, $category="post") {
    foreach (glob("$category-$id-*.php") as $_) {
        return true;
    }
    return false;
}

$id = getenv("id");
$title = addslashes(getenv("title"));
$description = addslashes(@getenv("description"));
$category = @getenv("category");
$date = time();

if (!$category) {
    $category = "post";
}
if (preg_match("/[^0-9a-zA-Z]/", $category)) {
    echo "category must consist of alphanumeric characters only.\n";
    exit;
}

if (!$title) {
    echo "set title in ENV\n";
    echo "optionally set description, and id\n";
    exit;
}

if (!$id) {
    $id = nextID($category);
} else if (preg_match("/[^0-9]/", $id)) {
    echo "id must be an +integer\n";
    exit;
}


if (postExists($id, $category)) {
    echo "$category id=$id is already used.\n";
    exit;
}

$filename = "$category-$id.php";

$contents = "
<?php
require_once('lib/yapssg.php');

renderPost([
    'id' => $id,
    'title' => '$title',
    'date' => $date,
    'description' => '$description',
    'category' => getCategoryByFilename(__FILE__),
]);
";

file_put_contents($filename, $contents);
file_put_contents("content/$category-$id.md", "
# Hello

This is $category $id.

Add more markdown content here.
");

echo "$category created with id=$id\n";