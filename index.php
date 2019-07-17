<?php
require_once("lib/yapssg.php");

render([], function () {
    
    $numPosts = 5;
    ?>
<div class="site-nav">
    <a href="#">link</a>
    <a href="#">other link</a>
    <a href="<?=sitelink('about')?>">about</a>
</div>

<h1>Documentation</h1>
<ul>
<?php foreach (array_reverse(allPosts("doc")) as $post) { ?>
<li>
    <a href="<?=postlink($post)?>"><?=$post["title"] ?? 'untitled post ' . $post['id'] ?></a>
</li>
<?php } ?>
</ul>

<h1>Blog</h1>
<ul>
<?php foreach (array_reverse(allPosts("post")) as $post) { ?>
<li>
    <a href="<?=postlink($post)?>"><?=$post["title"] ?? 'untitled post ' . $post['id'] ?></a>
</li>
<?php } ?>
</ul>

<hr>


<?php });
