<?php
if (php_sapi_name() != 'cli') header("Content-Type: text/plain");
$fail = $pass = $skip = 0;
chdir(isset($argv[1]) ? $argv[1] : __DIR__);
foreach (glob("*.php") as $file){
  if ((realpath($file)) == __FILE__) continue;
  $name = mb_substr($file, 0, -4);
  if (!is_file("$name.expect")) touch("$name.expect");
  $out = array();
  exec('php -f ' . escapeshellarg($file) . ' 2>&1', $out, $exit);
  $out = implode("\n", $out) . "\n";
  if ($exit > 0 && $exit < 255)
    $skip += print("SKIPPED $name: $out[0]\n");
  elseif ($out !== file_get_contents("$name.expect")){
    $fail += print("FAILED $name\n");
    file_put_contents("$name.actual", $out);
    shell_exec('diff -u -- ' . escapeshellarg("$name.expect")
      . ' ' .  escapeshellarg("$name.actual") . ' > '
      . escapeshellarg("$name.diff")
    );
  }
  else{
    $pass += print("PASSED $name\n");
    if (is_file("$name.actual")) unlink("$name.actual");
    if (is_file("$name.diff")) unlink("$name.diff");
  }
}
printf("PASSED: %d, FAILED: %d, SKIPPED: %d (%.0F ms)\n", $pass, $fail,
  $skip, (microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']) * 1000);
exit($fail ? 1 : 0);
