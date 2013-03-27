<?php

namespace Microjade;

class HtmlNode extends Node{

  const PATTERN = '{^\s* (?<tag>[\w\d#\.]+) (?:\((?<attr>.*)\))? (?<unformated>\.?) (?<text>.*)$}x';
  private $selfClosing = 'area base br col hr img input link meta source';


  public function __construct($line){
    parent::__construct($line);
    if (preg_match(self::PATTERN, $line, $m)){
      $classes = $ids = array();
      $parts = preg_split('{([\.#])}', $m['tag'], null, PREG_SPLIT_DELIM_CAPTURE);
      $tag = 'div';
      for ($i = 0; $i < count($parts); $i++){
        if ($parts[$i] == '.'){
          $classes[] = $parts[$i++ + 1];
        }
        elseif ($parts[$i] == '#'){
          $ids[] = $parts[$i++ + 1];
        }
        elseif (!empty($parts[$i]))
          $tag = $parts[$i];
      }
      if (!in_array($tag, explode(' ', $this->selfClosing)))
        $this->closingTag = '</' . $tag . '>';
      $this->openingTag = '<' . $tag
        . ($ids ? ' id="' .  implode(' ', $ids) . '"' : '')
        . ($classes ? ' class="' .  implode(' ', $classes) . '"' : '')
        . ($m['attr'] ? ' ' : '') . $m['attr']
        . ($this->closingTag ? '>' : '/>');
      $this->text = trim($m['text']);
      if (!empty($m['unformated']))
        $this->filter = [$this, 'filter'];
    }
  }

  public function filter($line){
    return new TextNode($line, TextNode::TRIM);
  }
}
