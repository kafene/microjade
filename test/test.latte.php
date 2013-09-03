MicrojadeLatte test
<?php
require __DIR__ . '/../src/MicrojadeLatte.php';

$compileLatte = function($s){
  var_dump((new MicrojadeLatte)->compile($s, true));
};

$compileLatte('
- $x = 1
<!DOCTYPE html>
- if $x > 1
  p= "x: " . $x
- elseif $x == 1
  p(class="{!$x}") text
- else
  | text
ul- foreach array(1, 2) as $i
  li= $i
.footer {}
- cache
  p cached
- block a
  b inblock
- nopair
.end
');
