<?php
require_once("lib/index.php");

render([], function () { ?>

<ul>
<?php foreach (allPosts() as $post) { ?>
<li>
    <a href="<?=postlink($post['id'])?>"><?=$post["title"] ?? 'untitled post ' . $post['id'] ?></a>
</li>
<?php } ?>
</ul>

<?php });
