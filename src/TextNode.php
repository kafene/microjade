<?php

namespace Microjade;

class TextNode extends Node{

  const PATTERN = '{^\s*\|\s*(.*)$}';
  const STRIP = 1;
  const TRIM = 2;
  const ESCAPE = 4;

  public function __construct($line, $flags = self::STRIP){
    parent::__construct($line);
    $this->text = $line;
    if ($flags & self::TRIM)
      $this->text = ltrim($this->text);
    if ($flags & self::ESCAPE)
      $this->text = htmlspecialchars($this->text);
    if ($flags & self::STRIP)
      $this->text = preg_replace(self::PATTERN, '\1', $this->text);
  }

  public function getIndentString(){
    return null;
  }
}
