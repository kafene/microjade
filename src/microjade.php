<?php

class Microjade{

  static private $patterns = [
    'block' => '~^(if|else|elseif|for|foreach|while|block)\b\s*(.*)$~',
    'php' => '~^(\-|=|\$|\!=?)\s*(.*)$~',
    'html' => '~^([\w\d_\.\#\-]+)(.*)$~',
    'text' => '~^(\|)?(.*)$~'
  ];
  static private $token = [
    'open' => null, 'close' => null,
    'else' => false, 'textBlock' => false
  ];

  static public function compile($input, $showIndent = false){
    $lines = explode("\n", str_replace("\r", '', $input));
    $output = $textBlock = null;
    $closing = array();
    foreach ($lines as $n => $line){
      $token = self::$token;
      $indent = mb_strlen($line) - mb_strlen(ltrim($line));
      $indentStr = ($showIndent && !$textBlock) ? str_repeat(' ', $indent) : '';
      if ($textBlock !== null && $textBlock < $indent)
        $token['open'] = htmlspecialchars(ltrim($line));
      else{
        $token = self::parseLine($line);
        $textBlock = null;
      }
      foreach (array_reverse($closing, true) as $i => $code){
        if ($i >= $indent){
          if (!$token['else'] || $i != $indent)
            $output .= $code;
          unset($closing[$i]);
        }
      }
      $output .= "\n" . $indentStr . $token['open'];
      $closing[$indent] = $token['close'];
      if ($token['textBlock']) $textBlock = $indent;
    }
    return $output;
  }

  static private function parseLine($line){
    $line = trim($line, "\t\n ");
    $token = self::$token;
    foreach (self::$patterns as $name => $pattern){
      if (preg_match($pattern, $line, $match)){
        if ($name == 'text')
          $token['open'] = self::parseInline($match[2]);
        else
          $token = call_user_func_array("self::parse" . ucfirst($name), $match);
        break;
      }
    }
    return $token;
  }

  static private function parseBlock($line, $type, $code){
    $token = self::$token;
    if ($type == 'block'){
      $token['open'] = "<?php if(isset(\$$code)) echo \$$code; else{ ob_start();\$_blocks[]=\"$code\" ?>";
      $token['close'] = '<?php $_block=array_pop($_blocks);echo $$_block=ob_get_clean();}?>';
    }
    else{
      $token['open'] = "<?php $type ($code): ?>";
      if ($type == 'else') $token['open'] = "<?php $type: ?>";
      $token['close'] = "<?php end$type ?>";
      if (in_array($type, ['else', 'elseif']))
        $token['else'] = !!$token['close'] = "<?php endif ?>";
    }
    return $token;
  }

  static private function parseHtml($line, $type, $code){
    $token = self::$token;
    $m = array_fill(0, 5, null);
    preg_match('~^([\w\d\-_]+)?  ([\.\#][\w\d\-_\.\#]*[\w\d])?
      (\([^\)]+\))?  (\.)?  ((\-|=|\!=?)|:)? \s* (.*) ~x', $line, $m);
    $token['open'] = empty($m[1]) ? '<div' : "<$m[1]";
    $token['close'] = empty($m[1]) ? '</div>' : "</$m[1]>";
    if (!empty($m[2])){
      $id = preg_filter('~.*(\#([^\.]*)).*~', '\2', $m[2]);
      $token['open'] .= $id ? " id=\"$id\"" : '';
      $classes = preg_replace('~\#[^\.]*~', '', $m[2]);
      $classes = str_replace('.', ' ', $classes);
      $token['open'] .= $classes ? ' class="' . trim($classes) . '"' : '';
    }
    if (!empty($m[3]))
      $token['open'] .= ' ' . self::parseInline(trim($m[3], '() '));
    $token['textBlock'] = !empty($m[4]);
    if (!empty($m[5])){
      $nexttoken = self::parseLine($m[6] . $m[7]);
      $token['open'] .= '>' . $nexttoken['open'];
      $token['close'] = $nexttoken['close'] . $token['close'];
    }
    else
      $token['open'] .= ">" . self::parseInline($m[7]);
    return $token;
  }

  static private function parsePhp($line, $type, $code){
    $token = self::$token;
    if ($type == '-')
      $token['open'] = "<?php $code ?>";
    elseif ($type == '!=' || $type == '!')
      $token['open'] = "<?php echo $code ?>";
    elseif ($type == '=')
      $token['open'] = "<?php echo htmlspecialchars($code, ENT_QUOTES) ?>";
    elseif ($type == '$')
      $token['open'] = "<?php echo htmlspecialchars(\$$code, ENT_QUOTES) ?>";
    return $token;
  }

  static private function parseInline($input){
    return preg_replace_callback('~{ (/?) ([^\}\n]*) }~x', function($m){
      if (preg_match(self::$patterns['block'], $m[2])
         || preg_match(self::$patterns['php'], $m[2])){
        $token = self::parseLine($m[2]);
        return empty($m[1]) ? $token['open'] : $token['close'];
      }
      return $m[0];
    }, $input);
  }
}
