# Microjade

Converts templates with [jade][]-like syntax to PHP or [Latte][].

[jade]: https://github.com/visionmedia/jade
[latte]: https://github.com/nette/latte


## Usage

```php
$template = (new Microjade)->compile(file_get_contents('example.jade'));
file_put_contents('example.php', $template);
require('example.php');
```
For usage in Nette Framework see [nette.md](nette.md).


## Supported syntax

    <html>           - untouched
    p(id="t1") Text  - html tag
    link(abc)/       - self-closing tag
    .class#id        - implicit div
    | text           - unformated line
    - code           - php code
    = $var           - escaped variable
    != $var          - unescaped variable
    -# comment       - single-line comment
    // comment       - html block comment
    script.          - unformated block
    if, while, for.. - php blocks
    block name       - print $name or saves block to $name


## Inline macros

These macros can be used anywhere in template except in unformated blocks (like `script.`).
Inline macros are left untouched for Latte output.

    {$var}                   - prints escaped variable
    {!$var}                  - prints unescaped variable
    {-any_php_code()}        - alias for <?php ... ?>
    {=any_php_code()}        - prints escaped result
    {!any_php_code()}        - prints unescaped result
    {block name} {/block}    - prints $name or saves block to $name
    {if $x} {elseif $y} {else} {/if}
    {while $x} {/while}
    {foreach $x as $y} {/foreach}
    {for $i=1; $i<=10; $i++} {/for}


## Todo

- don't proccess `<?php` block
- `switch`, `case` and `default` blocks

