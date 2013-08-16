Php code in jade
<?php
require __DIR__ . '/setup.php';

$compile('
- $x = "<b>value</b>"
= "escaped: " . $x
!= "unescaped: " . $x
-# comment
-# {$notphp}
p= "php in element"
p {="inline code"}
p {-$x = 1}
p {$x}
p {!$x}
p {!"unescaped"}
');
