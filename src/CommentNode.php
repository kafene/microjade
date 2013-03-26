<?php

namespace Microjade;

class CommentNode extends Node{

  const REGEX_COMMENT = '{^\s*//\-?(.*)$}';
  const REGEX_COMMENT_HIDDEN = '{^\s*//\-.*$}';

  public function __construct($line){
    parent::__construct($line);
    if (preg_match(self::REGEX_COMMENT, $line, $matches)){
      $this->text = trim($matches[1]);
      if (preg_match(self::REGEX_COMMENT_HIDDEN, $line)){
        $this->openingTag = '<?php /* ';
        $this->closingTag = ' */ ?>';
      }
      else{
        $this->openingTag = '<!-- ';
        $this->closingTag = ' -->';
      }
      $this->unformated = true;
    }
  }

  public static function test($line){
    return preg_match(self::REGEX_COMMENT, $line);
  }
}
