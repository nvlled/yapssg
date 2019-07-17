<?php
require_once("slug.php");
require_once("config.php");
require_once("layout.php");
require_once("Parsedown.php");

function mdFile($postID, $category="post")
{
    $filename = "content/$category-$postID.md";
    if (!file_exists($filename)) {
        return;
    }
    $markdown = file_get_contents($filename);
    $Parsedown = new Parsedown();
    $html = $Parsedown->text($markdown);
    return "<div class='markdown-content'>$html</div>";
}

function md($markdown)
{
    $Parsedown = new Parsedown();
    return  $Parsedown->text($markdown);
}

function allPosts($categoryFilter="")
{
    if (@$GLOBALS["ALL_POSTS"]) {
        $posts = $GLOBALS["ALL_POSTS"];
        if ($categoryFilter == "") {
            return $posts;
        }
        return array_filter($posts, function ($post) use ($categoryFilter) {
            return $post["category"] == $categoryFilter;
        });
    }

    $posts = [];

    foreach (glob("*-*.php") as $filename) {
        if (!preg_match("/([0-9a-zA-Z]+)-[0-9]+.php/", $filename, $match)) {
            //echo "not a page: $filename, skipping<br>";
            continue;
        }
        $category = $match[1];

        $content = file_get_contents($filename);
        preg_match('/\$post\s*=\s*\[(.*)\]\s*;/ms', $content, $m);
        if (!$m) {
            preg_match('/renderPost\s*\(\s*\[\s*(.*)\s*\]\s*\);/ms', $content, $m);
        }
        eval("\$post = [{$m[1]}];");
        $post["category"] = $category;


        if ($post && $post['id'] && !@$post["draft"]) {
            array_push($posts, $post);
        }
    }

    usort($posts, function ($a, $b) {
        return $b['date'] <=> $a['date'];
    });

    $GLOBALS["ALL_POSTS"] = $posts;

    if ($categoryFilter == "") {
        return $posts;
    }
    return array_filter($posts, function ($post) use ($categoryFilter) {
        return $post["category"] == $categoryFilter;
    });
}

function postMap()
{
    if (@$GLOBALS["MAP_PAGES"]) {
        return $GLOBALS["MAP_PAGES"];
    }

    $posts = [];
    foreach (allPosts() as $post) {
        $k = "{$post["category"]}-{$post["id"]}";
        $posts[$k] = $post;
    }
    $GLOBALS["MAP_PAGES"] = $posts;
    return $posts;
}

function getPost($id) {
    $posts = postMap();
    return $posts[$id];
}

function adjacentPosts($id)
{
    $posts = allPosts();
    $index = null;
    foreach ($posts as $i => $post) {
        if ($post["id"] == $id) {
            $index = $i;
            break;
        }
    }
    if ($index === null) {
        return [];
    }
    return [
        "prev" => @$posts[$index+1],
        "next" => @$posts[$index-1],
    ];
}

function mapPosts($posts)
{
    $map = [];
    foreach ($posts as $post) {
        $map[$post["id"]] = $post;
    }
    return $map;
}

function recentPosts($count=5)
{
    return array_slice(allPosts(), 0, $count);
}

function postlink($post)
{
    $id = $post["id"];
    $category = $post["category"];
    $title = generateUrlSlug($post["title"]);
    if (@$GLOBALS['DEPLOY']) {
        return "$category-$id-$title.html";
    }
    return "$category-$id.php";
}

function sitelink($path)
{
    if (@$GLOBALS['DEPLOY']) {
        return "$path.html";
    }
    return "$path.php";
}


function parsePageFilename($filename) {
    $count = preg_match("/([0-9a-zA-Z]+)-([0-9])+\.php/", $filename, $m);
    if ($count == 0) {
        return null;
    }
    return [
        "id" => @$m["2"],
        "category" => @$m["1"],
    ];
}

function getCategoryByFilename($filename) {
    $data = parsePageFilename($filename);
    return $data["category"];
}