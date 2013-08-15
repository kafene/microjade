<?php
require __DIR__ . '/../src/microjade.php';

$compile = function($s){
  $php = Microjade::compile($s, true);
  var_dump($php);
  ob_start();
  eval('?>' . $php);
  var_dump(ob_get_clean());
};
