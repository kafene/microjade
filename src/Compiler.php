<?php

namespace Microjade;

class Compiler {

  const REGEX_EMPTY_LINE = '{^\s*$}';

  public static function compileFile($filename){
    return self::compile(file_get_contents($filename));
  }

  public static function compile($template){
    $template = str_replace("\r", '', $template);
    $lines = explode("\n", rtrim($template) . "\n");
    $output = null;
    $emptyLines = null;
    $closing = array();
    $parentNode = false;

    foreach ($lines as $n => $line){
      if (preg_match(self::REGEX_EMPTY_LINE, $line) && $n != count($lines) - 1){
        $emptyLines .= "\n";
        continue;
      }

      $indent = (mb_strlen($line) - mb_strlen(ltrim($line)));
      if ($parentNode !== false && $parentNode->getIndent() >= $indent)
        $parentNode = false;

      if ($parentNode !== false && $parentNode->hasFilter())
        $node = $parentNode->filter($line);
      else
        $node = self::getNode($line);

      if ($node->hasFilter())
        $parentNode = $node;

      $closingTags = null;
      foreach ($closing as $key => $item){
        if ($key >= $indent){
          if (!($key == $indent && $node->isElseTag()))
            $closingTags = $item . $closingTags;
          $closing = array_diff_key($closing, array($key => true));
        }
      }

      $closing[$indent] = $node->getClosingTag();

      if (class_exists('\Tracy\Debugger'))
        \Tracy\Debugger::barDump($node);

      $output .= $closingTags . "\n" . $emptyLines
        . $node->getIndentString() . $node->getOpeningTag()
        . $node->getText();
      $emptyLines = null;
    }
    return trim($output);
  }

  private static function getNode($line){
    if (TextNode::test($line))
      return new TextNode($line);
    elseif (DoctypeNode::test($line))
      return new DoctypeNode($line);
    elseif (CommentNode::test($line))
      return new CommentNode($line);
    elseif (HtmlNode::test($line))
      return new HtmlNode($line);
    elseif (PhpNode::test($line))
      return new PhpNode($line);
    elseif (LatteNode::test($line))
      return new LatteNode($line);
    else
      return new TextNode($line, 0);
  }
}
