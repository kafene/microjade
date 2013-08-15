# Microjade

Converts templates with [jade][]-like syntax to PHP.

[jade]: https://github.com/visionmedia/jade

## Supported syntax

    <html>           - untouched
    p(id="t1") Text  - html tags
    .class#id        - implicit div
    | text           - unformated lines
    - code           - php code
    = $var           - escaped variable
    != $var          - unescaped variable
    -# comment       - unbuffered comment
    textarea.        - unformated block
    if, while, for.. - php blocks
    block name       - print $name or saves block to $name

## Inline macros

    {$var}                   - show escaped variable
    {!$var}                  - show unescaped variable
    {-any_php_code()}        - alias for <?php ... ?>
    {=any_php_code()}        - show escaped result
    {block name} {/block}    - show $name or create it
    {foreach $x as $y} {/foreach}
    {if $x} {elseif $y} {else} {/if}
    {while $x} {/while}

## Todo

    // comment       - html comment
    //if lt IE 8     - conditional comments
    switch|case|default

## Usage

```php
$template = Microjade::compile(file_get_contents('example.jade'));
file_put_contents('example.php', $template);
require('example.php');
```
