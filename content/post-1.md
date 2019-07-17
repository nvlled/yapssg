
I've made some changes so that posts can be categoried by filename.
So there would be the general post-1.php posts, then maybe
some docs-1.php posts, etc. It was tricky change to make.
I tried all sorts of stuffs, including debug_backtrace().
I wanted to avoid being redundant when specifying category,
one in the file contents, and in the filename.

In the end, I resorted to just doing getCategoryByFilename(\__FILE__),
which isn't too bad. All this trouble is due to the no-database approach.