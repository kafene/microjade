<?php

namespace Microjade;

class HtmlNode extends Node{

  const PATTERN = '{^\s*(?<tag>[\w\d]+) (?:\((?<attr>.*)\))? (?<unformated>\.?) (?<text>.*)$}x';

  public function __construct($line){
    parent::__construct($line);
    if (preg_match(self::PATTERN, $line, $m)){
      $this->openingTag = '<' . $m['tag']
        . ($m['attr'] ? ' ' : '') . $m['attr'] . '>';
      $this->closingTag = '</' . $m['tag'] . '>';
      $this->text = htmlspecialchars(trim($m['text']));
      if (!empty($m['unformated']))
        $this->filter = [$this, 'filter'];
    }
  }

  public function filter($line){
    return new TextNode($line, TextNode::TRIM);
  }
}
