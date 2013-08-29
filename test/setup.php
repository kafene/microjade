<?php
require __DIR__ . '/../src/Microjade.php';

$compile = function($s){
  $mjade = new Microjade;
  $php = $mjade->compile($s, true);
  var_dump($php);
  ob_start();
  eval('?>' . $php);
  ob_end_clean();
};
