<?php

class Microjade{

  protected $patterns = [
    'block' => '~^(if|else|elseif|for|foreach|while|block)\b\s*(.*)$~',
    'php' => '~^(\-|=|\$|\!=?)\s*(.*)$~',
    'html' => '~^([\w\d_\.\#\-]+)(.*)$~',
    'text' => '~^(\|)?(.*)$~'
  ];
  protected $token = [
    'open' => null, 'close' => null,
    'else' => false, 'textBlock' => false
  ];

  public function compile($input, $showIndent = false){
    $lines = explode("\n", str_replace("\r", '', $input));
    $output = $textBlock = null;
    $closing = [];
    foreach ($lines as $n => $line){
      $token = $this->token;
      $indent = mb_strlen($line) - mb_strlen(ltrim($line));
      $indentStr = ($showIndent && !$textBlock) ? str_repeat(' ', $indent) : '';
      if ($textBlock !== null && $textBlock < $indent)
        $token['open'] = htmlspecialchars(ltrim($line));
      else{
        $token = $this->parseLine($line);
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

  protected function parseLine($line){
    $line = trim($line, "\t\n ");
    $token = $this->token;
    foreach ($this->patterns as $name => $pattern){
      if (preg_match($pattern, $line, $match)){
        if ($name == 'text')
          $token['open'] = $this->parseInline($match[2]);
        else
          $token = call_user_func_array([$this, "parse" . ucfirst($name)], $match);
        break;
      }
    }
    return $token;
  }

  protected function parseBlock($line, $type, $code){
    $token = $this->token;
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

  protected function parseHtml($line, $type, $code){
    $token = $this->token;
    $m = array_fill(0, 5, null);
    preg_match('~^([\w\d\-_]+)? ([\.\#][\w\d\-_\.\#]*[\w\d])?
      (\([^\)]+\))? (/)? (\.)? ((\-|=|\!=?)|:)? \s* (.*) ~x', $line, $m);
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
      $token['open'] .= ' ' . $this->parseInline(trim($m[3], '() '));
    $token['close'] = empty($m[4]) ? $token['close'] : '';
    $token['open'] .= empty($m[4]) ? '>' : " />";
    $token['textBlock'] = !empty($m[5]);
    if (!empty($m[6])){
      $nexttoken = $this->parseLine($m[7] . $m[8]);
      $token['open'] .= $nexttoken['open'];
      $token['close'] = $nexttoken['close'] . $token['close'];
    }
    else
      $token['open'] .= $this->parseInline($m[8]);
    return $token;
  }

  protected function parsePhp($line, $type, $code){
    $token = $this->token;
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

  protected function parseInline($input){
    return preg_replace_callback('~{ (/?) ([^\}\n]*) }~x', function($m){
      if (preg_match($this->patterns['block'], $m[2])
         || preg_match($this->patterns['php'], $m[2])){
        $token = $this->parseLine($m[2]);
        return empty($m[1]) ? $token['open'] : $token['close'];
      }
      return $m[0];
    }, $input);
  }
}
