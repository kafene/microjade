Comment blocks in jade
<?php
require __DIR__ . '/setup.php';

$compile('
// single-line comment
div
  // multi line
     comment
     here
');
