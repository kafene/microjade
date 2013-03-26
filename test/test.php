<?php

require_once(__DIR__ . '/../vendor/autoload.php');

if (class_exists('\Tracy\Debugger'))
  \Tracy\Debugger::enable();

$input = file_get_contents(__DIR__ . '/example.jade');
$output = Microjade\Parser::parse($input);


ob_start();
?>
<title>Microjade example</title>

<pre>{input}</pre>
<pre>{output}</pre>
</pre>
<style>
  body{
    max-width: 40em;
    margin: 2em auto;
  }
  pre{
    border: 1px solid gray;
    padding: .5em;
  }
</style><?php

eval('?>' . preg_replace(
  '/{(\w+)}/',
  '<?php echo htmlspecialchars($\1)?>',
  ob_get_clean()
));
