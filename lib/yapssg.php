<?php
require_once("slug.php");
require_once("config.php");
require_once("layout.php");
require_once("Parsedown.php");

function mdFile($postID)
{
    $filename = "content/post-$postID.md";
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

function allPosts()
{
    if (@$GLOBALS["ALL_POSTS"]) {
        return $GLOBALS["ALL_POSTS"];
    }

    $posts = [];

    foreach (glob("post-*.php") as $filename) {
        $content = file_get_contents($filename);
        preg_match('/\$post\s*=\s*\[(.*)\]\s*;/ms', $content, $m);
        if (!$m) {
            preg_match('/renderPost\s*\(\s*\[\s*(.*)\s*\]\s*\);/ms', $content, $m);
        }
        eval("\$post = [{$m[1]}];");

        if ($post && $post['id'] && !@$post["draft"]) {
            array_push($posts, $post);
        }
    }

    usort($posts, function ($a, $b) {
        return $b['date'] <=> $a['date'];
    });

    $GLOBALS["ALL_POSTS"] = $posts;
    return $posts;
}

function postMap() {
    $posts = [];
    foreach (allPosts() as $post) {
        $posts[$post["id"]] = $post;
    }
    return $posts;
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
    $title = generateUrlSlug($post["title"]);
    if (@$GLOBALS['DEPLOY']) {
        return "post-$id-$title.html";
    }
    return "post-$id.php";
}

function pagelink($path)
{
    if (@$GLOBALS['DEPLOY']) {
        return "$path.html";
    }
    return "$path.php";
}
