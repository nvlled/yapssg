#!/usr/bin/env php
<?php
chdir(dirname(dirname(__FILE__)));
echo "# deleting build directory\n";
system("rm -rf ./build");
echo "done\n";
