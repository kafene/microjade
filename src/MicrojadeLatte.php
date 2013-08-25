<?php
require_once __DIR__ . '/Microjade.php';

class MicrojadeLatte extends Microjade{

  public function __construct(){
    unset($this->patterns['block']);
  }

  protected function parsePhp($token){
    list($type, $code) = array_slice($token['match'], 1, 2);
    if ($type == '-'){
      $token['open'] = "{? $code}";
      $keyword = preg_replace('~\s.*~', '', $code);
      if ($token['isBlock']){
        $token['open'] = "{{$code}}";
        $token['else'] = (mb_strpos($keyword, 'else') === 0);
        $token['close'] = "{/}";
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
