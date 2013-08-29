Php code in jade
<?php
require __DIR__ . '/setup.php';

$compile('
- $x = "<b>value</b>"
ul- foreach (array(1, 2) as $i)
  li= $i
- while(is_int("x"))
  div test
= "escaped: " . $x
!= "unescaped: " . $x
-# comment
-# {$notphp}
p= "php in element"
p- "php in element"
p.class- "php in element"
p {="inline code"}
p {-$x = 1}
p {$x}
p {!$x}
p {!"unescaped"}
');
