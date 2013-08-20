Html elements in jade
<?php
require __DIR__ . '/setup.php';

$compile('
<!DOCTYPE html>
html(lang="en" data-type="test" flag  ): body
  head: link(rel="selfclosing")/
  ul.x(title="list")
    li.y(title="item"): b multilevel
  p {="inline code"}
  #id.class.another default element is div
  p(attr="nested(brackets())")
  p(attr="nested(brackets())")- print("ok")
  pre.class.
    text
  #id#id2.class#id3 should set id3 only
');
