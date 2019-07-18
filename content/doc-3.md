
The entirety of the codebase is pretty simple, so making
changes should be relatively easy. Unlike other SSG,
which uses a crippled templating language, this one
uses PHP, so it has all the niceities and perils
of a programming language.

There are a few organizational conventions to make things
simpler. One is that all pages should preferrably be in the root project directory.
This is for easier deployment, the HTML content can be place in any sub-directory.
And post content are place in markdown files in content/

The PHP files created with ./bin/new-post.php can be completely
modified.