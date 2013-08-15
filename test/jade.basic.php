Tests basic jade structure and element nesting

<?php
require __DIR__ . '/setup.inc';

$compile('
<!DOCTYPE html>
html
  <head>
    title Hello
  </head>
  body
    .menu
    #content
      ul:
        | text
        li: b item1
        li: b item2
        li: b item3
');
