<?php

namespace Microjade;

class TextNode extends Node{
  public function __construct($line){
    $this->text = $line;
  }
}
