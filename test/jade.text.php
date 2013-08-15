Tests text nodes in jade
<?php
require __DIR__ . '/setup.inc';

$compile('
-$x = 1
p unescaped text in html > {$x}
| unescaped text block > {$x}
pre.
  escaped text, not php > {$x}
');
