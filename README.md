Saq StaticHelperBundle
=================

Бандл являет собой по сути сборку различных хелперов

Установка
------------

### Composer
```bash
$ php composer require saq/statichelperbundle
```
или можно добавить в конфиг composer.json строку вида
```json
{
    "require" : {
        "saq/statichelperbundle": "^1.0"
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

use Saq\StaticHelperBundle\Helper\AppSaq;

/**
 * class: App
 */
class App extends AppSaq
{ 
	 // здесь можно дополнять или переопределять статические методы
}
```
После это будут достпны к вызову многие методы из App класса, например
получим объект щаблонизатора Twig
```php
$oTwig = App::getContainer()->get('twig');
```

Наследоваться не обязательно, можно сразу использовать классы хелпера, например
```php
$oTwig = AppSaq::getContainer()->get('twig');
```