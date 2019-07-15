
# Introduction

This is an example page that is also
an instruction how to use.

## Adding new post
To add a new post, run the following:

```
$ title='your title' ./bin/new-post.php
```
Two files will be created, a php file and a markdown file in the content/ directory.


---

## Linking to other pages
To make internal links work both on developement and production,
use the following helper functions: (see index.php for example usage)
```
postlink($postID)
pagelink('name-of-php-file') # without file extension
```

---

## Deployment
To generate html files for deployment, run:
```
$ ./bin/build.php
```
