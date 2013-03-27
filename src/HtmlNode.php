<?php

namespace Microjade;

class HtmlNode extends Node{

  const REGEX_ELEMENT = '{^\s*(?<tag>[\w\d]+) (?:\((?<attr>.*)\))? (?<unformated>\.?) (?<text>.*)$}x';

  public function __construct($line){
    parent::__construct($line);
    if (preg_match(self::REGEX_ELEMENT, $line, $m)){
      $this->openingTag = '<' . $m['tag']
        . ($m['attr'] ? ' ' : '') . $m['attr'] . '>';
      $this->closingTag = '</' . $m['tag'] . '>';
      $this->text = htmlspecialchars(trim($m['text']));
      if (!empty($m['unformated']))
        $this->filter = [$this, 'filter'];
    }
  }

  public static function test($line){
    return preg_match(self::REGEX_ELEMENT, $line);
  }

  public function filter($line){
    return new TextNode($line, TextNode::TRIM);
  }
}
