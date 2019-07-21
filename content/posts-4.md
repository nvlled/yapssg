
Just some braindump here.

Let's see, I'm thinking of what to add next.
It's working as it is, but it seems like there's
something missing. Tags would be nice addition,
maybe rss feed too. What about search? Sitemap?
Breadcrumbs.

It is still possible to add heirarchal content
even if the page filesystem layout is flat.
Maybe I could add something like:

```php
$sitemap = [
    "blog" => [
        "notes",
        "life"
    ],
    "tutorials" => [
        "javascript",
        "go",
    ],
];
```

Hmm, yeah this would be a nice way to organize content.
Pages under the javascript category would have the breadcrumb trail:

```
tutorials >> javascript >> page title 1
tutorials >> javascript >> page title 2
```

---

For tags, I'd have a list a tags at the bottom of each post,
then clicking them would go to a page where other posts
with similar tags are shown. Or not, I may need to use js for
this one.

Here's another idea, why not make parse things in php tags
```<?php echo ('like this' ?> ```
So the pipeline would be php -> markdown -> html

Oh, I should add a formatter so the page source would be nicely formatted.

Another thing, what if I need to link some internal pages in here...
like this [oh crap](posts-1)
Well, nothing a regex can't fix. At least this is easier to do in
markdown than in an PHP or HTML file.

Lastly, if I would be post a bunch of code snippets here, I should find
me some syntax highligher for php.
