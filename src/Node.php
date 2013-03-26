<?php

namespace Microjade;

class Node{

  protected $openingTag = null;
  protected $closingTag = null;
  protected $unformated = false;
  protected $text = null;
  protected $attr = null;
  protected $line = null;

  public function __construct($line){
    $this->line = $line;
  }

  public function getIndentString(){
    return mb_substr($this->line, 0,
      mb_strlen($this->line) - mb_strlen(ltrim($this->line)));
  }

  public function getOpeningTag(){
    return $this->openingTag;
  }

  public function getClosingTag(){
    return $this->closingTag;
  }

  public function getText(){
    return $this->text;
  }

  public function isUnformated(){
    return $this->unformated;
  }
}
