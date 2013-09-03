<?php
/**
 * Beast PHP test runner
 * Runs all ./test.*.php files and prints results.
 * Exit codes that can be used in scripts:
 * 0: compare output with expect/$filename.txt
 * 1: failed, 2: skipped, 3: passed
 */
header("Content-Type: text/plain");
$pass = $fail = $skip = array();
chdir(isset($argv[1]) ? $argv[1] : __DIR__);
foreach (glob("test.*.php") as $file){
  $name = basename($file, '.php');
  if (!is_dir("expect")) mkdir("expect", 0755);
  if (!is_file("expect/$name.txt")) touch("expect/$name.txt");
  $out = array();
  exec('php -f ' . escapeshellarg($file) . ' 2>&1', $out, $exit);
  if ($exit == 2)
    $skip[] = $name . !print('s');
  elseif ($exit == 3 || (!$exit && implode("\n", $out) . "\n"
      === file_get_contents("expect/$name.txt")))
    $pass[] = print('.');
  else{
    $fail[] = $name . !print('f');
    file_put_contents("$name.txt", implode("\n", $out) . "\n");
    shell_exec('diff -u -- ' . escapeshellarg("expect/$name.txt") . ' '
      .  escapeshellarg("$name.txt") . ' > ' . escapeshellarg("$name.diff"));
    continue;
  }
  if (is_file("$name.txt")) unlink("$name.txt");
  if (is_file("$name.diff")) unlink("$name.diff");
}
if (count($skip)) echo "\nSKIPPED: " . implode(', ', $skip);
if (count($fail)) echo "\nFAILED: " . implode(', ', $fail);
printf("\nTOTAL %d:%d%s (%.0Fms)\n", count($pass), count($fail),
  count($skip) ? " (" . count($skip) . " skipped)" : '',
  (microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']) * 1000);
exit($fail ? 1 : 0);
