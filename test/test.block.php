Php blocks in jade
<?php
require __DIR__ . '/setup.php';

$compile('
-$i = 1
blocks
  block content
    |hello
  if true
    p ok
    if false
      p nested
    else
      p nested
  else
    p liar
  ul: while $i <= 3
    li= $i++
inline
  {if true}x{else}y{/if}
  {block content}world{/block}
');
