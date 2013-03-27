<?php

namespace Microjade;

class TextNode extends Node{

  const REGEX_TEXT = '{^\s*\|\s*(.*)$}';

  public function __construct($line, $isUnformated = false){
    parent::__construct($line);
    if ($isUnformated)
      $this->text = ltrim($line);
    else{
      $this->text = preg_replace(self::REGEX_TEXT, '\1', $line);
      $this->text = htmlspecialchars($this->text);
    }
  }

  public static function test($line){
    return preg_match(self::REGEX_TEXT, $line);
  }

  public function getIndentString(){
    return null;
  }
}
