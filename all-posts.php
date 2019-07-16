<?php
require_once("lib/yapssg.php");

render([], function () { ?>

<ul>
<?php foreach (allPosts() as $post) { ?>
    <li>
        <a href="<?=postlink($post)?>"><?=$post["title"] ?? 'untitled post ' . $post['id'] ?></a>
    </li>
<?php } ?>
</ul>

<?php });
