<?php

require_once 'Parsedown.php';
require_once 'config.php';
require_once 'slug.php';
require_once 'categories.php';
require_once 'layout.php';

$GLOBALS['categories'] = $categories;

function mdFile($name)
{
    $filename = "content/$name.md";
    if (!file_exists($filename)) {
        return;
    }
    $markdown = file_get_contents($filename);
    $html = md($markdown);

    return "<div class='markdown-content'>$html</div>";
}

function md($markdown)
{
    $Parsedown = new Parsedown();
    $markdown = preg_replace_callback('/\[(.*)\]\((.*)\)/', function ($matches) {
        $text = $matches[1];
        if (!$text) {
            $text = $matches[2];
        }
        $link = sitelink($matches[2]);

        return "[$text]($link)";
    }, $markdown);

    return  $Parsedown->text($markdown);
}

function allPages()
{
    allPosts();

    return $GLOBALS['ALL_PAGES'];
}

function allPosts($categoryFilter = '')
{
    if (@$GLOBALS['ALL_POSTS']) {
        $posts = $GLOBALS['ALL_POSTS'];

        return filterPostsByCategory($categoryFilter, $posts);
    }

    $posts = [];
    $pages = [];

    $prevPosts = [];
    $index = 0;
    foreach (glob('*.php') as $filename) {
        if (!preg_match('/([0-9a-zA-Z]+)-[0-9]+.php/', $filename, $match)) {
            array_push($pages, str_replace('.php', '', $filename));
            continue;
        }
        $category = $match[1];

        $content = file_get_contents($filename);
        preg_match('/\$post\s*=\s*\[(.*)\]\s*;/ms', $content, $m);
        if (!$m) {
            preg_match('/renderPost\s*\(\s*\[\s*(.*)\s*\]\s*\);/ms', $content, $m);
        }
        eval("\$post = [{$m[1]}];");
        $post['category'] = $category;

        if ($post && $post['id'] && !@$post['draft']) {
            $prevPost = @$prevPosts[$category];
            if ($prevPost) {
                $post['prev'] = $prevPost;
                $prevPost['next'] = $post;
            }

            $post['index'] = $index;
            $posts[$post['index']] = $post;
            $posts[$prevPost['index']] = $prevPost;
            $prevPosts[$category] = $post;
        }
        ++$index;
    }

    $GLOBALS['ALL_POSTS'] = $posts;
    $GLOBALS['ALL_PAGES'] = $pages;

    return filterPostsByCategory($categoryFilter, $posts);
}

function filterPostsByCategory($categoryFilter, $posts)
{
    if ($categoryFilter === '') {
        return $posts;
    }

    return array_filter($posts, function ($post) use ($categoryFilter) {
        return $post['category'] === $categoryFilter;
    });
}

function postMap()
{
    if (@$GLOBALS['MAP_PAGES']) {
        return $GLOBALS['MAP_PAGES'];
    }

    $posts = [];
    foreach (allPosts() as $post) {
        $k = "{$post['category']}-{$post['id']}";
        $posts[$k] = $post;
    }
    $GLOBALS['MAP_PAGES'] = $posts;

    return $posts;
}

function getPost($category, $id)
{
    $posts = postMap();
    $k = "$category-$id";

    return @$posts[$k];
}

//function getPosts($category)
//{
//    return array_filter(allPosts(), function ($post) use ($category) {
//        return $post['category'] == $category;
//    });
//}

function adjacentPosts($id)
{
    $posts = allPosts();

    return [
        'prev' => @$posts[$index + 1],
        'next' => @$posts[$index - 1],
    ];
}

function mapPosts($posts)
{
    $map = [];
    foreach ($posts as $post) {
        $map[$post['id']] = $post;
    }

    return $map;
}

function recentPosts($count = 5)
{
    return array_slice(allPosts(), 0, $count);
}

function postlink($post)
{
    $id = $post['id'];
    $category = @$post['category'];
    $title = generateUrlSlug($post['title']);
    if (@$GLOBALS['DEPLOY']) {
        return "$category-$id-$title.html";
    }

    return "$category-$id.php";
}

function sitelink($path)
{
    if ($path == 'home') {
        $path = 'index';
    }
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
        'id' => @$m['2'],
        'category' => @$m['1'],
    ];
}

function getCategoryByFilename($filename)
{
    $data = parsePageFilename($filename);

    return $data['category'];
}

function sortByDate($posts)
{
    usort($posts, function ($a, $b) {
        return $b['date'] <=> $a['date'];
    });

    return $posts;
}

function createTree($value, $children)
{
    $childNodes = (object) [];
    $node = (object) [
        'value' => $value,
    ];
    foreach ($children as $k => $v) {
        if (!is_string($k)) {
            $childNodes->{$v} = (object) [
                'value' => $v,
                'parent' => $node,
                'children' => (object) [],
            ];
        } else {
            $subNode = createTree($k, $v);
            $subNode->parent = $node;
            $childNodes->{$k} = $subNode;
        }
    }
    $node->children = $childNodes;

    return $node;
}

function flattenTree($node)
{
    $array = [$node->value => $node];
    foreach ($node->children as $child) {
        foreach (flattenTree($child) as $grandChild) {
            if (@$array[$grandChild->value] != null) {
                throw new Error("Duplicate key: {$grandChild->value}");
            }
            $array[$grandChild->value] = $grandChild;
        }
    }

    return (object) $array;
}

function createCategoryTree()
{
    if (!@$GLOBALS['CATEGORY_TREE']) {
        $GLOBALS['CATEGORY_TREE'] = createTree('home', $GLOBALS['categories']);
    }

    return $GLOBALS['CATEGORY_TREE'];
}

function createCategoryMap()
{
    if (!@$GLOBALS['CATEGORY_MAP']) {
        $GLOBALS['CATEGORY_MAP'] = flattenTree(createCategoryTree());
    }

    return $GLOBALS['CATEGORY_MAP'];
}

// ['aaa', '>>', 'bbb', '>>', 'ccc']
// page listing for each category
//
function breadcrumb($category, $id)
{
    $categories = createCategoryMap();
    $post = getPost($category, $id);
    $trail = [
        (object) [
            'id' => "{$post['category']}-{$post['id']}",
            'link' => postlink($post),
            'text' => @$post['title'] ?? "Unknown post: $category-$id",
        ],
    ];
    $category = @$categories->{$category};

    if (!$category) {
        $category = $post['category'];
        array_unshift($trail, (object) [
            'link' => sitelink($category),
            'text' => $category,
        ]);
        array_unshift($trail, (object) [
            'link' => sitelink('index'),
            'text' => 'home',
        ]);

        return $trail;
    }

    while ($category) {
        array_unshift($trail, (object) [
            'link' => sitelink($category->value),
            'text' => $category->value,
        ]);
        $category = @$category->parent;
    }

    return $trail;
}

function renderBreadcrumb($category, $id)
{
    $items = breadcrumb($category, $id);
    foreach ($items as $i => $item) {
        $filename = $item->id ?? $item->text;
        if (file_exists("$filename.php")) {
            ?>
<a href="<?=$item->link; ?>"><?=ucfirst($item->text); ?></a>
<?php
        } else {
            ?>
<span><?=ucfirst($item->text); ?></span>
<?php
        } ?>
<?php

        if ($i < count($items) - 1) {
            echo ' >> ';
        }
    }
}

//var_dump(breadcrumb('javascript', '1'));
