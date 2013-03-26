<?php

namespace Microjade;

class Node{

  protected $openingTag = null;
  protected $closingTag = null;
  protected $unformated = false;
  protected $text = null;
  protected $attr = null;

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
