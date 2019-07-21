<?php

require_once 'lib/yapssg.php';

function renderSitemap($node)
{
    ?>
<div id="<?=$node->value; ?>">> <a href="<?=sitelink($node->value); ?>"><?=$node->value; ?></a></div>
<ul>
    <?php
        foreach (allPosts($node->value) as $post) {
            ?> <li><a href="<?=postlink($post); ?>"><?=$post['title'] ?? 'untitled post '.$post['id']; ?></a></li>
    <?php
        }
    foreach ($node->children as $subNode) {
        renderSitemap($subNode);
    } ?>
</ul>
<?php
}
?>

<?php

render([],
function () {
    ?>
<div class="sitemap">
    <h2>Sitemap</h2>
            <?php
    $tree = createCategoryTree();
    foreach ($tree->children as $node) {
        renderSitemap($node);
    } ?>
    </div>

</div>
<?php
}); ?>