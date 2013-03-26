<?php

namespace Microjade;

class CommentNode extends Node{

  const REGEX_COMMENT = '{^//\-?(.*)$}';
  const REGEX_COMMENT_HIDDEN = '{^//\-.*$}';

  public function __construct($line){
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
