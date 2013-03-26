# Microjade

Converts templates with [jade][]-like syntax to PHP.

[jade]: https://github.com/visionmedia/jade

## Done

- `p(id="t1") Text.` - basic html tags
- `textarea.` - unformated text in tags
- `// html comment`
- `//- php comment`
- `doctype 5`
- `| text` in TextNode

## Todo

- `- code` and `= $var` in PhpNode
- `.class#id` in HtmlNode
- `#{}` and `\#{}` in Node
- escape text in Node
- `//if lt IE 8` in CommentNode
- `h1(title = $var)` in HtmlNode

## Usage

```php
$template = Microjade\Parser::parseFile('example.jade');
file_put_contents('example.php', $template);

require(__DIR__ . '/example.php');
```
