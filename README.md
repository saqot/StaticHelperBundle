Saq StaticHelperBundle
=================
[![Build Status](https://secure.travis-ci.org/saqot/StaticHelperBundle.png?branch=master)](http://travis-ci.org/saqot/StaticHelperBundle)
[![Latest Stable Version](https://poser.pugx.org/saq/statichelperbundle/v/stable)](https://packagist.org/packages/saq/statichelperbundle)
[![Total Downloads](https://poser.pugx.org/saq/statichelperbundle/downloads)](https://packagist.org/packages/saq/statichelperbundle)

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
        "saq/statichelperbundle": "^1.2"
    }
}
```
Регистрируем бандл. Это необходимо для возможности получения контейнера хелпером.
Добаляем в файл app/AppKernel.php строку вида

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

### AppSaq

Method                                              | Description
--------------------------------------------------- | --------------------------------------------------
`AppSaq::getKernel();`                              | объект KernelInterface
`AppSaq::getContainer();`                           | объект ContainerInterface
`AppSaq::getParameter();`                           | получение значений из app/config/parameters.yml
`AppSaq::getTranslator();`                          | объект Translator
`AppSaq::getDoctrine();`                            | объект DoctrineBundle
`AppSaq::getRepository();`                          | получение Репозитория указанной сущности
`AppSaq::em();`                                     | объект EntityManager
`AppSaq::getRequest();`                        		| объект Request
`AppSaq::getTwig();`                                | объект шаблонизатора Twig
`AppSaq::getSession();`                             | объект для работы с сессиями
`AppSaq::getLogger();`                              | логгер
`AppSaq::dump();`                                   | VarDumper от SF
`AppSaq::dumpExit();`                               | VarDumper от SF c прерываением кода


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