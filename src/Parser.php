<?php

namespace Microjade;

class Parser {

  const REGEX_EMPTY_LINE = '{^\s*$}';

  public static function parseFile($filename){
    return self::parse(file_get_contents($filename));
  }

  public static function parse($template){
    $template = str_replace("\r", '', $template);
    $lines = explode("\n", rtrim($template) . "\n");
    $output = null;
    $emptyLines = null;
    $closing = array();
    $unformated = false;

    foreach ($lines as $n => $line){
      if (preg_match(self::REGEX_EMPTY_LINE, $line) && $n != count($lines) - 1){
        $emptyLines .= "\n";
        continue;
      }

      $indent = (mb_strlen($line) - mb_strlen(ltrim($line)));
      if ($unformated !== false && $indent <= $unformated)
        $unformated = false;

      $closingTags = null;
      foreach ($closing as $key => $item){
        if ($key >= $indent){
          $closingTags = $item . $closingTags;
          $closing = array_diff_key($closing, array($key => true));
        }
      }

      $node = self::getNode($line, $unformated !== false);
      $closing[$indent] = $node->getClosingTag();
      if ($node->isUnformated())
        $unformated = $indent;

      if (class_exists('\Tracy\Debugger'))
        \Tracy\Debugger::barDump($node);

      $output .= $closingTags . "\n" . $emptyLines
        . $node->getIndentString() . $node->getOpeningTag()
        . $node->getText();
      $emptyLines = null;
    }
    return trim($output);
  }

  private static function getNode($line, $isUnformated = false){
    if ($isUnformated)
      return new TextNode($line, true);
    elseif (TextNode::test($line))
      return new TextNode($line);
    elseif (DoctypeNode::test($line))
      return new DoctypeNode($line);
    elseif (CommentNode::test($line))
      return new CommentNode($line);
    elseif (HtmlNode::test($line))
      return new HtmlNode($line);
    elseif (PhpNode::test($line))
      return new PhpNode($line);
    else
      return new Node($line);
  }
}
