<?php

namespace Microjade;

class CommentNode extends Node{

  const PATTERN = '{^\s*//\-?(.*)$}';
  const PATTERN_HIDDEN= '{^\s*//\-.*$}';

  public function __construct($line){
    parent::__construct($line);
    if (preg_match(self::PATTERN, $line, $matches)){
      $this->text = trim($matches[1]);
      if (preg_match(self::PATTERN_HIDDEN, $line)){
        $this->openingTag = '<?php /* ';
        $this->closingTag = ' */ ?>';
      }
      else{
        $this->openingTag = '<!-- ';
        $this->closingTag = ' -->';
      }
      $this->filter = function($line){
        return new TextNode($line, 0);
      };
    }
  }
}
