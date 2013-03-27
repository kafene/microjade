# Microjade

Converts templates with [jade][]-like syntax to PHP.
[Latte][] macros are also supported.

[jade]: https://github.com/visionmedia/jade
[latte]: http://doc.nette.org/en/default-macros

## Done

    doctype 5        - doctypes
    p(id="t1") Text  - html tags
    .class#id        - implicit div
    textarea.        - unformated blocks
    | text           - unformated lines
    - code           - php code
    = $var           - escaped variable
    != $var          - unescaped variable
    // comment       - html comment
    //- comment      - unbuffered comment

## Todo

    <meta ../>       - self-closing html tags
    #{} and \#{}     - inline php code
    //if lt IE 8     - conditional comments

## Usage

```php
$template = Microjade\Compiler::compileFile('example.jade');
file_put_contents('example.php', $template);

require(__DIR__ . '/example.php');
```
