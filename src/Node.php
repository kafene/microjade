<?php

namespace Microjade;

class Node{

  const PATTERN = '{.*}';
  protected $openingTag;
  protected $closingTag;
  protected $text;
  protected $line;
  protected $filter;
  protected $elseTag;

  public function __construct($line){
    $this->line = $line;
  }

  public static function test($line){
    $className = get_called_class();
    return preg_match($className::PATTERN, $line);
  }

  public function getIndent(){
    return mb_strlen($this->line) - mb_strlen(ltrim($this->line));
  }

  public function getIndentString(){
    return mb_substr($this->line, 0, $this->getIndent());
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

  public function isElseTag(){
    return $this->elseTag;
  }

  public function hasFilter(){
    return is_callable($this->filter);
  }

  public function filter($line){
    return call_user_func($this->filter, $line);
  }
}
