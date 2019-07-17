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
    <header class="site-header">
        <a class="site-name" href="<?=pagelink("index")?>"><?= SITE_NAME ?></a>
        <span class="site-desc"><?= SITE_DESC ?></span>
    </header>
    <div class="site-content">
    <?php $body(); ?>
    </div>
    <footer>EOF</footer>
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
        $adjacent = @adjacentPosts($post["id"]);
        $prevPost = @$adjacent["prev"];
        $nextPost = @$adjacent["next"]; ?>
<div class="post-container">
    <div class="post-title"><?=@$post['title']?></div>
    <div class="post-description"><?=@$post['description']?></div>
    <div class="post-date"><?=@date("l jS \of F Y h:i:s A", $post['date'])?></div>

    <div class="post-content">
        <?= @$post["content"] ?>
        <?php if (@$post["id"]) { ?>
            <?= mdFile($post["id"]) ?>
        <?php } ?>
    </div>

    <?php if ($prevPost) { ?>
        <a href="<?=postlink($prevPost)?>">Previous: <?=$prevPost['title']?></a>
    <?php } ?>
    <br>
    <?php if ($nextPost) { ?>
        <a href="<?=postlink($nextPost)?>">Next: <?=$nextPost['title']?></a>
    <?php } ?>

</div>
<?php
    });
}
?>