Tests html elements in jade
<?php
require __DIR__ . '/setup.inc';

$compile('
<!DOCTYPE html>
html(lang="en" data-type="test" flag  ): body
  head: link(rel="selfclosing")/
  ul.x(title="list")
    li.y(title="item"): b multilevel
  p {="inline code"}
  #id.class.another default element is div
  pre.class.
    text
  #id#id2.class#id3 should set id3 only
');
