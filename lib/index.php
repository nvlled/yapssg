<?php
require_once("config.php");
require_once("layout.php");
require_once("Parsedown.php");

$deploy = false;

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
    $posts = [];

    foreach (glob("post-*.php") as $filename) {
        ob_start();
        include $filename;
        ob_end_clean();
        //$content = file_get_contents($filename);
        //$q = '[\'"]';
        ////preg_match("/{$q}title$q.*=>.*{$q}(.*){$q}.*,/", $content, $m);
        //preg_match('/renderPost\(\[(.*)\]\);/ms', $content, $m);
        //eval ("\$post = [{$m[1]}];");
        if ($post && $post['id'] && !@$post["draft"]) {
            array_push($posts, $post);
        }
    }

    usort($posts, function ($a, $b) {
        return $b['date'] <=> $a['date'];
    });

    //$prevPost = null;
    //foreach ($posts as $post) {
    //    if ($prevPost) {
    //        $prevPost["next"] = $post;
    //        $post["prev"] = $prevPost;
    //    }
    //    $prevPost = $post;
    //}

    // TODO: order by date, and slice to count
    return $posts;
}

function adjacentPosts($posts, $id)
{
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

function postlink($id)
{
    if ($GLOBALS['DEPLOY']) {
        return "post-$id.html";
    }
    return "post-$id.php";
}

function pagelink($path)
{
    if ($GLOBALS['DEPLOY']) {
        return "$path.html";
    }
    return "$path.php";
}

$ALL_POSTS = allPosts();
