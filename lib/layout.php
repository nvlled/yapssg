<?php

function render($args, $body)
{
    // header
    $pageTitle = SITE_NAME;
    if (@$args['title']) {
        $pageTitle = "{$args['title']} -- $pageTitle";
    } ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title><?= $pageTitle; ?></title>

    <link rel="stylesheet" href="resources/css/vendor/highlight.css">
    <script src="resources/js/vendor/highlight.js"></script>
    <link rel="stylesheet" href="resources/css/site.css" />

    <head>
        <div class="site-wrapper">
            <header class="site-header">
                <a class="site-name" href="<?=sitelink('index'); ?>"><?= SITE_NAME; ?></a>
                <span class="site-desc"><?= SITE_DESC; ?></span>
            </header>
            <div class="site-content">
                <?php $body(); ?>
            </div>
            <footer>EOF</footer>
        </div>
        <script>hljs.initHighlightingOnLoad();</script>
        </body>

</html>

<?php
}

function renderPost($post)
{
    $category = $post['category'];
    $renderLayout = @$GLOBALS['categoryLayouts'][$category];
    if ($renderLayout) {
        $renderLayout($post);

        return;
    }

    render([
        'title' => @$post['title'],
    ], function () use ($post) {
        $post = getPost($post['category'], $post['id']);
        $prevPost = @$post['prev'];
        $nextPost = @$post['next']; ?>
<div class="post-container">
        <?php renderBreadcrumb($post['category'], $post['id']); ?>
    <div class="post-title"><?=@$post['title']; ?></div>
    <div class="post-description"><?=@$post['description']; ?></div>
    <div class="post-date"><?=@date("l jS \of F Y h:i:s A", $post['date']); ?></div>

    <div class="post-content">
        <?= @$post['content']; ?>
        <?php if (@$post['id']) {
            ?>
        <?= mdFile("{$post['category']}-{$post['id']}"); ?>
        <?php
        } ?>
    </div>

    <div class="post-nav">
        <?php if ($prevPost) {
            ?>
        <a href="<?=postlink($prevPost); ?>">Previous: <?=$prevPost['title']; ?></a>
        <?php
        } ?>
        <br>
        <?php if ($nextPost) {
            ?>
        <a href="<?=postlink($nextPost); ?>">Next: <?=$nextPost['title']; ?></a>
        <?php
        } ?>
    </div>

</div>
<?php
    });
}

$categoryLayouts = [
    'doc' => function ($post) {
        render([
        'title' => @$post['title'],
    ], function () use ($post) {
        $post = getPost($post['category'], $post['id']);
        $nextPost = @$post['next']; ?>
<div class="post-container">
        <?php renderBreadcrumb($post['category'], $post['id']); ?>
    <div class="post-title"><?=@$post['title']; ?></div>
    <div class="post-description"><?=@$post['description']; ?></div>

    <div class="post-content">
        <?= @$post['content']; ?>
        <?php if (@$post['id']) {
            ?>
        <?= mdFile("{$post['category']}-{$post['id']}"); ?>
        <?php
        } ?>
    </div>

    <?php if ($nextPost) {
            ?>
    <a href="<?=postlink($nextPost); ?>">Next: <?=$nextPost['title']; ?></a>
    <?php
        } ?>

</div>
<?php
    });
    },
];
$GLOBALS['categoryLayouts'] = $categoryLayouts;

?>