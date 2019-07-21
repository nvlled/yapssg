# Prerequisites
* git
* php7
* text editor
* browser

# Quick Start

1. ```sh
$ git clone https://github.com/nvlled/yapssg your-site-name
```

1. ```sh
$ cd your-site-name
```

1. ```sh
change site details in config.php
```

1. ```sh
$ title='Hello world' ./bin/new-post
```

1. ```sh
Edit created markdown file in content/
```

# Development
The usual make-changes-then-F5 cycle applies while
developing.

Run ```./dev.sh``` to use the builtin php webserver,
then open the browser at http://localhost:7000 .

Or if prefer you can put the project directory
on your local webserver.

# Deploying
Run ```./bin/build.sh``` to generate HTML content.
The output will be in output/ directory, which you
can just simply copy to your remote server.

## Linking to other pages
To make internal links work both on developement and production,
use the following helper functions: (see index.php for example usage)
```
<a href="<?=postlink($postID)?>">link to post</a>

<a href="<?pagelink('about')?>">link to about.php</a>
```
Note: You must omit the file extension when using pagelink.

## Adding resources
You put your images, css and other files in the resources folder,
and link to them in your pages as you would normally do.
For easier deployment, you should just use relative paths.