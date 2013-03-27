<?php

namespace Microjade;

class LatteNode extends Node{

  const PATTERN = '{^\s* (?<el>\{ (?<tag>[\w_]+) .* \}) (?<text>.*) }x';

  private $pairTags = 'block if ifset ifCurrent foreach for while first last sep capture cache syntax _ form label snippet';
  private $elseTags = array(
    'elseif' => 'if',
    'else' => 'if',
    'elseifset' => 'ifset',
  );

  public function __construct($line){
    parent::__construct($line);
    if (preg_match(self::PATTERN, $line, $m)){
      $this->openingTag = $m['el'];
      if (in_array($m['tag'], explode(' ', $this->pairTags)))
        $this->closingTag = '{/' . $m['tag'] . '}';
      if (isset($this->elseTags[$m['tag']])){
        $this->closingTag = '{/' . $this->elseTags[$m['tag']] . '}';
        $this->elseTag = true;
      }
      $this->text = trim($m['text']);
    }
  }
}
