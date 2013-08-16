# Microjade in Nette

To use Microjade templates in Nette Framework add this methods into `BasePresenter`.
Then all templates with extension `.jade` will be precompiled with Microjade.

```php
<?php

abstract class BasePresenter extends Nette\Application\UI\Presenter{

  public function templatePrepareFilters($template){
    $template->registerFilter(function($input) use ($template){
      if (mb_substr($template->getFile(), -5) == '.jade')
        return (new MicrojadeLatte)->compile($input);
      else return $input;
    });
    parent::templatePrepareFilters($template);
  }

  public function formatJadeTemplateFiles($list){
    $jadeList = array();
    foreach ($list as $item){
      if (mb_substr($item, -6) == '.latte')
        $jadeList[] = mb_substr($item, 0, -6) . '.jade';
    }
    return array_merge($jadeList, $list);
  }

  public function formatLayoutTemplateFiles(){
    $list = parent::formatLayoutTemplateFiles();
    return self::formatJadeTemplateFiles($list);
  }

  public function formatTemplateFiles(){
    $list = parent::formatTemplateFiles();
    return self::formatJadeTemplateFiles($list);
  }
}
```
