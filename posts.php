<?php
require_once 'lib/yapssg.php';

render(['title' => 'Posts'], function () {
    ?>

<h2>Posts</h2>

<?=md('
This page shows all the shitty posts I made.
'); ?>

<ul>
    <?php foreach (sortByDate(allPosts('posts')) as $post) {
        ?>
    <li>
        <a href="<?=postlink($post); ?>"><?=$post['title'] ?? 'untitled post '.$post['id']; ?></a>
        <small><?=date('m/d/Y', $post['date']); ?></small>
    </li>
    <?php
    } ?>
</ul>

<?php
});
