<?php

namespace Microjade;

class TextNode extends Node{

  const REGEX_TEXT = '{^\s*\|\s*(.*)$}';

  public function __construct($line, $inText = false){
    parent::__construct($line);
    if ($inText)
      $this->text = $line;
    else
      $this->text = preg_replace(self::REGEX_TEXT, '\1', $line);
  }

  public static function test($line){
    return preg_match(self::REGEX_TEXT, $line);
  }

  public function getIndentString(){
    return null;
  }
}
