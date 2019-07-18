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
        return filterPostsByCategory($categoryFilter, $posts);
    }

    $posts = [];

    $prevPosts = [];
    $index = 0;
    foreach (glob("*-*.php") as $filename) {
        if (!preg_match("/([0-9a-zA-Z]+)-[0-9]+.php/", $filename, $match)) {
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
            $prevPost = @$prevPosts[$category];
            if ($prevPost) {
                $post["prev"] = $prevPost;
                $prevPost["next"] = $post;
            }

            $post["index"] = $index;
            $posts[$post["index"]] = $post;
            $posts[$prevPost["index"]] = $prevPost;
            $prevPosts[$category] = $post;
        }
        $index++;
    }

    $GLOBALS["ALL_POSTS"] = $posts;

    return filterPostsByCategory($categoryFilter, $posts);
}

function filterPostsByCategory($categoryFilter, $posts)
{
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

function getPost($category, $id)
{
    $posts = postMap();
    $k = "$category-$id";
    return $posts[$k];
}

function adjacentPosts($id)
{
    $posts = allPosts();
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


function parsePageFilename($filename)
{
    $count = preg_match("/([0-9a-zA-Z]+)-([0-9])+\.php/", $filename, $m);
    if ($count == 0) {
        return null;
    }
    return [
        "id" => @$m["2"],
        "category" => @$m["1"],
    ];
}

function getCategoryByFilename($filename)
{
    $data = parsePageFilename($filename);
    return $data["category"];
}

function sortByDate($posts)
{
    usort($posts, function ($a, $b) {
        return $b['date'] <=> $a['date'];
    });
    return $posts;
};
