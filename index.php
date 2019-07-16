<?php
require_once("lib/yapssg.php");

render([], function () {
    
    $numPosts = 5;
    ?>

<ul>
<?php foreach (array_reverse(allPosts()) as $post) { ?>
<li>
    <a href="<?=postlink($post)?>"><?=$post["title"] ?? 'untitled post ' . $post['id'] ?></a>
</li>
<?php } ?>
</ul>
<a href="<?=pagelink('about')?>">about</a>


<?php });
