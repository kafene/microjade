<?php

namespace Microjade;

class PhpNode extends Node{

  const REGEX_CODE = '{^\s*(?<sign>\-|\!?=)\s*(?<code>.*)}';

  public function __construct($line){
    parent::__construct($line);
    if (preg_match(self::REGEX_CODE, $line, $m)){
      $this->openingTag = '<?php ';
      $this->closingTag = ' ?>';

      if ($m['sign'] == '!=')
        $this->openingTag = '<?php echo ';

      elseif ($m['sign'] == '='){
        $this->openingTag = '<?php echo htmlspecialchars(';
        $this->closingTag = ') ?>';
      }

      $this->text = trim($m['code']);
      $this->unformated = true;
      $this->filter = [$this, 'filter'];
    }
  }

  public static function test($line){
    return preg_match(self::REGEX_CODE, $line);
  }

  public function filter($line){
    return new TextNode($line);
  }
}
