<?php

namespace Microjade;

class PhpNode extends Node{

  const PATTERN = '{^\s*(?<sign>\-|\!?=)\s*(?<code>.*)}';

  public function __construct($line){
    parent::__construct($line);
    if (preg_match(self::PATTERN, $line, $m)){
      $this->openingTag = '<?php ';
      $this->closingTag = ' ?>';

      if ($m['sign'] == '!=')
        $this->openingTag = '<?php echo ';

      elseif ($m['sign'] == '='){
        $this->openingTag = '<?php echo htmlspecialchars(';
        $this->closingTag = ') ?>';
      }

      $this->text = trim($m['code']);
      $this->filter = [$this, 'filter'];
    }
  }

  public function filter($line){
    return new TextNode($line);
  }
}
