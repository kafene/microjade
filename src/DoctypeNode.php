<?php

namespace Microjade;

class DoctypeNode extends Node{

  const REGEX_DOCTYPE = '{^\s*doctype( .*)?$}';
  private $doctypes = [
    'default' => '<!DOCTYPE html>',
    '5' => '<!DOCTYPE html>',
    'xml' => '<?xml version="1.0" encoding="utf-8" ?>',
    'transitional' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">',
    'strict' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">',
    'frameset' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">',
    '1.1' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">',
    'basic' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML Basic 1.1//EN" "http://www.w3.org/TR/xhtml-basic/xhtml-basic11.dtd">',
    'mobile' => '<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.2//EN" "http://www.openmobilealliance.org/tech/DTD/xhtml-mobile12.dtd">',
  ];

  public function __construct($line){
    parent::__construct($line);
    if (preg_match(self::REGEX_DOCTYPE, $line, $matches)){
      $version = isset($matches) ? mb_strtolower(trim($matches[1])) : 'default';
      if (!array_key_exists($version, $this->doctypes))
        $version = 'default';
      $this->openingTag = $this->doctypes[$version];
    }
  }

  public static function test($line){
    return preg_match(self::REGEX_DOCTYPE, $line);
  }
}
