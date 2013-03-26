<?php

namespace Microjade;

class Parser {

  public static function parseFile($filename){
    return self::parse(file_get_contents($filename));
  }

  public static function parse($template){
    $template = str_replace("\r", '', $template);
    $lines = explode("\n", $template . "\n");
    $indent = 0;
    $output = null;
    $closing = array();
    $unformated = false;

    foreach ($lines as $n => $line){
      $indent = (mb_strlen($line) - mb_strlen(ltrim($line)));
      $indentString = mb_substr($line, 0, $indent);
      //$line = ltrim($line);
      if ($unformated !== false && $indent <= $unformated)
        $unformated = false;

      $closingTags = null;
      foreach ($closing as $key => $item){
        if ($key >= $indent){
          $closingTags = $item . $closingTags;
          $closing = array_diff_key($closing, array($key => true));
        }
      }

      $isText = ($unformated !== false && $indent > $unformated);
      $node = self::getNode($line, $isText);
      $closing[$indent] = $node->getClosingTag();
      if ($node->isUnformated())
        $unformated = $indent;
      if (class_exists('\Tracy\Debugger'))
        \Tracy\Debugger::barDump($node);

      $output .= $closingTags . "\n" . $node->getIndentString()
        . $node->getOpeningTag() . $node->getText();
    }
    return trim($output);
  }

  private static function getNode($line, $isText = false){
    if ($isText)
      return new TextNode($line, true);
    elseif (TextNode::test($line))
      return new TextNode($line);
    elseif (DoctypeNode::test($line))
      return new DoctypeNode($line);
    elseif (CommentNode::test($line))
      return new CommentNode($line);
    elseif (HtmlNode::test($line))
      return new HtmlNode($line);
    else
      return new Node($line);
  }
}
