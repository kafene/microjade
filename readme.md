# Microjade

Converts templates with [jade][]-like syntax to PHP.

[jade]: https://github.com/visionmedia/jade

## Done

- `p(id="t1") Text.` - basic html tags
- `textarea.` - unformated text in tags
- `// html comment`
- `//- php comment`
- `doctype 5`

## Todo

- `- code` and `= $var` in PhpNode
- `.class#id` in HtmlNode
- `| text` in TextNode
- `#{}` and `\#{}` in Node
- escape text in Node
- `//if lt IE 8` in CommentNode
- `h1(title = $var)` in HtmlNode

## Usage

```php
$input = file_get_contents(__DIR__ . '/example.jade');
$output = Microjade\Parser::parse($input);
file_put_contents(__DIR__ . '/example.php', $output);

require(__DIR__ . '/example.php');
```
