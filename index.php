<?php
require_once 'lib/yapssg.php';

render([], function () {
    $numPosts = 3; ?>
<div class="site-nav">
    <a href="<?=sitelink('sitemap'); ?>">sitemap</a>
    <a href="<?=sitelink('about'); ?>">about</a>
</div>

<h2>Documentation</h2>
<ul>
    <?php foreach (allPosts('documents') as $post) {
        ?>
    <li>
        <a href="<?=postlink($post); ?>"><?=$post['title'] ?? 'untitled post '.$post['id']; ?></a>
    </li>
    <?php
    } ?>
</ul>

<h2>Recent Posts</h2>
<ul>
    <?php foreach (array_slice(sortByDate(allPosts('posts')), 0, $numPosts) as $post) {
        ?>
    <li>
        <a href="<?=postlink($post); ?>"><?=$post['title'] ?? 'untitled post '.$post['id']; ?></a>
        <small><?=date('m/d/Y', $post['date']); ?></small>
    </li>
    <?php
    } ?>
</ul>
<a href="<?=sitelink('posts'); ?>">see more posts</a>


<?php
});
