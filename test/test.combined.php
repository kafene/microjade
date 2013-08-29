Code inside php blocks should be left untouched
<?php
require __DIR__ . '/setup.php';

$compile('<?php

$some = "variable";

?>
html
  body= $some
    #one
    #two
<?php
  "another";
  "code";
?>
');
