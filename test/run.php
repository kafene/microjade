<?php
/**
 * Beast PHP test runner
 * Runs all ./test.*.php files and prints results.
 * Exit codes that can be used in scripts:
 * 0: compare output with expect/$filename.txt
 * 1: failed, 2: skipped, 3: passed
 */
header("Content-Type: text/plain");
$pass = !!$fail = $skip = array();
chdir(isset($argv[1]) ? $argv[1] : __DIR__);
foreach (glob("test.*.php") as $file){
  if ((realpath($file)) == __FILE__) continue;
  $name = preg_replace('~test\.(.*)\.php~', '\1', $file);
  if (!is_dir("expect")) mkdir("expect", 0755);
  if (!is_file("expect/$file.txt")) touch("expect/$file.txt");
  $out = array();
  exec('php -f ' . escapeshellarg($file) . ' 2>&1', $out, $exit);
  $out = implode("\n", $out) . "\n";
  if ($exit == 2)
    $skip[] = $name . !print('s');
  elseif ($exit == 3 || ($exit != 1
      && $out === file_get_contents("expect/$file.txt")))
    $pass += print('.');
  else{
    $fail[] = $name . !print('f');
    file_put_contents("$file.txt", $out);
    shell_exec('diff -u -- ' . escapeshellarg("expect/$file.txt")
      . ' ' .  escapeshellarg("$file.txt") . ' > '
      . escapeshellarg("$file.diff")
    );
    continue;
  }
  if (is_file("$file.txt")) unlink("$file.txt");
  if (is_file("$file.diff")) unlink("$file.diff");
}
if (count($skip))
  echo "\nSKIPPED: " . implode(', ', $skip);
if (count($fail))
  echo "\nFAILED: " . implode(', ', $fail);
printf("\nTOTAL %d:%d%s (%.0Fms)\n",
  $pass, count($fail),
  count($skip) ? " (" . count($skip) . " skipped)" : '',
  (microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']) * 1000);
exit($fail ? 1 : 0);
