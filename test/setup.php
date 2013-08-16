<?php
require __DIR__ . '/../src/Microjade.php';

$compile = function($s){
  $php = (new Microjade)->compile($s, true);
  var_dump($php);
  ob_start();
  eval('?>' . $php);
  ob_end_clean();
};
