<?php
require_once("lib/index.php");

render([], function () { ?>

<h3>Recent Posts</h3>

<ul>
<?php foreach (recentPosts() as $post) { ?>
<li>
    <a href="<?=postlink($post['id'])?>"><?=$post["title"] ?? 'untitled post ' . $post['id'] ?></a>
</li>
<?php } ?>
</ul>
<a href="<?=pagelink('/all-posts')?>">view all</a> |
<a href="<?=pagelink('/about')?>">about</a>


<?php });
