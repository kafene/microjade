<?php

namespace Microjade;

class Parser {

  public static function parse($template){

    $template = str_replace("\r", '', $template);
    $lines = explode("\n", $template . "\n");
    $indent = 0;
    $output = null;
    $closing = array();
    $unformated = false;

    # proccess lines
    foreach ($lines as $n => $line){
      $indent = (mb_strlen($line) - mb_strlen(ltrim($line)));
      $indentSpaces = mb_substr($line, 0, $indent);
      $line = ltrim($line);
      if ($unformated !== false && $indent <= $unformated)
        $unformated = false;

      # closing
      $closingLine = null;
      $newClosing = array();
      foreach ($closing as $key => $item){
        if ($key >= $indent){
          $closingLine = $item . $closingLine;
        }
        else
          $newClosing[$key] = $item;
      }
      $closing = $newClosing;

      $isText = ($unformated !== false && $indent > $unformated);
      $node = self::getNode($line, $isText);
      $closing[$indent] = $node->getClosingTag();
      if ($node->isUnformated())
        $unformated = $indent;
      if (class_exists('\Tracy\Debugger'))
        \Tracy\Debugger::barDump($node);

      # format template
      $output .= $closingLine . "\n" . ($isText ? '' : $indentSpaces)
        . $node->getOpeningTag() . $node->getText();
    }
    return trim($output);
  }

  private static function getNode($line, $isText = false){

    if ($isText)
      return new TextNode($line);
    elseif (DoctypeNode::test($line))
      return new DoctypeNode($line);
    elseif (CommentNode::test($line))
      return new CommentNode($line);
    elseif (HtmlNode::test($line))
      return new HtmlNode($line);
    else
      return new Node();
  }
}
