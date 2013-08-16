<?php
require_once __DIR__ . '/Microjade.php';

class MicrojadeLatte extends Microjade{

  protected $nettePairs = [
    'if', 'elseif', 'else', 'ifset', 'elseifset', 'ifCurrent',
    'foreach', 'for', 'while', 'first', 'last', 'sep', 'capture',
    'cache', 'syntax', 'block', 'define',  'form', 'label', 'snippet'
  ];

  public function __construct(){
    unset($this->patterns['block']);
  }

  protected function parsePhp($line, $type, $code){
    $token = $this->token;
    if ($type == '-'){
      $token['open'] = "{? $code}";
      $keyword = preg_replace('~\s.*~', '', $code);
      if (in_array($keyword, $this->nettePairs)){
        $token['open'] = "{{$code}}";
        $token['else'] = (mb_strpos($keyword, 'else') === 0);
        $keyword = str_replace('else', '', $keyword);
        $keyword = $keyword ?: 'if';
        $token['close'] = "{/$keyword}";
      }
    }
    elseif ($type == '!=' || $type == '!')
      $token['open'] = "{!$code}";
    elseif ($type == '=')
      $token['open'] = "{{$code}}";
    elseif ($type == '$')
      $token['open'] = "{\$$code}";
    return $token;
  }

  protected function parseInline($input){
    return $input;
  }
}
