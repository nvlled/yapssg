<?php

function render($args, $body)
{
    // header
    $pageTitle = SITE_NAME;
    if (@$args["title"]) {
        $pageTitle = "{$args['title']} -- $pageTitle";
    } ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= $pageTitle ?></title>
    <link rel="stylesheet" href="resources/css/site.css" />
<head>
<div class="site-wrapper">
    <header>
        <h1><a href="<?=pagelink("index")?>"><?= SITE_NAME ?></a></h1>
    </header>
    <?php $body(); ?>
    <footer> [c] 20XX </footer>
</div>
</body>
</html>

<?php
}

function renderPost($post)
{
    render([
        "title" => @$post['title'],
    ], function () use ($post) {
        $posts = $GLOBALS["ALL_POSTS"];
        $adjacent = adjacentPosts($posts, $post["id"]);
        $prevPost = $adjacent["prev"];
        $nextPost = $adjacent["next"];
        ?>
<div class="post-container">
    <div class="post-title"><?=@$post['title']?></div>
    <div class="post-description"><?=@$post['description']?></div>
    <div class="post-date"><?=@date("l jS \of F Y h:i:s A", $post['date'])?></div>

    <div class="post-content">
    <?php if (@$post["content"]) { ?>
        <?= @$post["content"] ?>
    <?php } ?>
    <?php if (@$post["id"]) { ?>
        <?= mdFile($post["id"]) ?>
    <?php } ?>
    </div>

    <?php if ($prevPost) { ?>
        <a href="<?=postlink($prevPost['id'])?>">Previous: <?=$prevPost['title']?></a>
    <?php } ?>
    <br>
    <?php if ($nextPost) { ?>
        <a href="<?=postlink($nextPost['id'])?>">Next: <?=$nextPost['title']?></a>
    <?php } ?>

</div>
<?php
    });
}
