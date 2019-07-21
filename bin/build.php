#!/usr/bin/env php
<?php

function recurse_copy($src, $dst)
{
    $dir = opendir($src);
    @mkdir($dst);
    while (false !== ($file = readdir($dir))) {
        if (($file != '.') && ($file != '..')) {
            if (is_dir($src.'/'.$file)) {
                recurse_copy($src.'/'.$file, $dst.'/'.$file);
            } else {
                copy($src.'/'.$file, $dst.'/'.$file);
            }
        }
    }
    closedir($dir);
}

function main()
{
    require_once 'lib/yapssg.php';
    $GLOBALS['DEPLOY'] = true;
    $deploy = true;
    chdir(dirname(dirname(__FILE__)));

    $posts = postMap();

    @mkdir('build');
    echo "# rendering php to html\n";
    foreach (glob('*.php') as $filename) {
        ob_start();
        include $filename;
        $html = ob_get_contents();
        ob_end_clean();

        $filenameData = parsePageFilename($filename);
        $category = @$filenameData['category'];
        $postID = @$filenameData['id'];

        $post = @$posts["$category-$postID"];
        if (!$post) {
            $destFilename = preg_replace('/\\.php$/i', '.html', $filename);
        } else {
            $title = generateUrlSlug($post['title']);
            $destFilename = "$category-{$post['id']}-{$title}.html";
        }
        echo "> build/$destFilename\n";
        file_put_contents("build/$destFilename", $html);
    }
    echo "done\n";

    echo "# copying static resources\n";
    system('rsync -h --update -v -r resources/ build/resources');
    echo "done\n";
}

main();
