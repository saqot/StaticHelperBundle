Saq StaticHelperBundle
=================

Бандл являет собой по сути сборку различных хелперов

Установка
------------

### Composer
```bash
$ php composer require saq/statichelperbundle:dev-master
```
или можно добавить в конфиг composer.json строку вида
```json
{
    "require" : {
        "saq/statichelperbundle": "dev-master"
    }
}
```
Регистрируем бандл. Добаляем в файл app/AppKernel.php строку вида
```php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Saq\StaticHelperBundle\SaqStaticHelperBundle(),
        // ...
    );
}
```

Настройка
-------------
Создаем класс для хелпера, желаельно с наиболее коротким именем, для удобства.
Например
```php
<?php

namespace WebBundle\Helper;

use Saq\StaticHelperBundle\SaqStaticHelperBundle;

/**
 * class: App
 */
class App extends SaqStaticHelperBundle
{ 
	 // здесь можно дополнять или переопределять статические методы
}
```
После это будут достпны к вызову многие методы из App класса, например
получим объект щаблонизатора Twig
```php
$oTwig = App::getContainer()->get('twig');
```