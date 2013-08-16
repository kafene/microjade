Tests comment nodes in jade
<?php
require __DIR__ . '/setup.inc';

$compile('
// single-line comment
div
  // multi line
     comment
     here

');
