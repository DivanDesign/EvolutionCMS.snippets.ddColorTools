# (MODX)EvolutionCMS.snippets.ddColorTools

Преобразует цвет в соответствии со смещением по тону, яркости или насыщенности.


## Использует

* PHP >= 5.6
* [(MODX)EvolutionCMS.libraries.ddTools](https://code.divandesign.ru/modx/ddtools) >= 0.60
* [(MODX)EvolutionCMS.snippets.ddGetDocumentField](https://code.divandesign.ru/modx/ddgetdocumentfield) >= 2.11.1


## Установка


### Вручную


#### 1. Элементы → Сниппеты: Создайте новый сниппет со следующими параметрами

1. Название сниппета: `ddColorTools`.
2. Описание: `<b>3.2.1</b> Преобразует цвет в соответствии со смещением по тону, яркости или насыщенности.`.
3. Категория: `Core`.
4. Анализировать DocBlock: `no`.
5. Код сниппета (php): Вставьте содержимое файла `ddColorTools_snippet.php` из архива.


#### 2. Элементы → Управление файлами

1. Создайте новую папку `assets/snippets/ddColorTools/`.
2. Извлеките содержимое архива в неё (кроме файла `ddColorTools_snippet.php`).


### Используя [(MODX)EvolutionCMS.libraries.ddInstaller](https://github.com/DivanDesign/EvolutionCMS.libraries.ddInstaller)

Просто вызовите следующий код в своих исходинках или модуле [Console](https://github.com/vanchelo/MODX-Evolution-Ajax-Console):

```php
//Подключение (MODX)EvolutionCMS.libraries.ddInstaller
require_once(
	$modx->getConfig('base_path') .
	'assets/libs/ddInstaller/require.php'
);

//Установка (MODX)EvolutionCMS.snippets.ddColorTools
\DDInstaller::install([
	'url' => 'https://github.com/DivanDesign/EvolutionCMS.snippets.ddColorTools',
	'type' => 'snippet'
]);
```

* Если `ddColorTools` отсутствует на вашем сайте, `ddInstaller` просто установит его.
* Если `ddColorTools` уже есть на вашем сайте, `ddInstaller` проверит его версию и обновит, если нужно. 


## Описание параметров


### Исходный цвет

* `inputColor`
	* Описание: Исходный цвет в HEX, HSL или HSB/HSV, с альфа-каналом или без.  
		Значение регистронезависимо.  
		Примеры валидных значений:
		* `ffffff`
		* `#FFFFFF`
		* `#FFFFFFFF`
		* `hsl(0, 0%, 100%)`
		* `hsla(0, 0%, 100%)`
		* `HSL(0, 0, 100)`
		* `hsb(0, 0%, 100%)`
		* `hsba(0, 0%, 100%)`
		* `hsv(0, 0%, 100%)`
		* `hsva(0, 0%, 100%)`
		* `hsb(0, 0, 100)`
		* `hsv(0, 0, 100)`
	* Допустимые значения: `string`
	* **Обязателен**
	
* `inputColor_docField`
	* Описание: Имя поля документа / TV, содержащего значение.  
		Если задать этот параметр, параметр `inputColor` игнорируется, значение получается из указанного поля документа.
	* Допустимые значения: `string`
	* Значение по умолчанию: —
	
* `inputColor_docId`
	* Описание: ID документа, значение поля которого нужно получить.  
		Если не задан, берётся ID текущего документа.
	* Допустимые значения: `integer`
	* Значение по умолчанию: —


### Модификация цвета

Все параметры могут содержать следующие специальные операторы:
1. `+` (например, `+10`) — прибавить
2. `-` (например, `-10`) — отнять
3. `abs` — округлить до максимального или минимального значения
4. `r` — инвертировать
5. без оператора (например, `10`) — просто установить, как указано

* `offset_h`
	* Описание: Операции смещения цветового тона через запятую.
	* Допустимые значения: `stringCommaSeparated`
	* Значение по умолчанию: `'+0'`
	
* `offset_h[i]`
	* Описание: Смещение цветового тона в градусах (`[-360; +360]`).
	* Допустимые значения: `string`
	* **Обязателен**
	
* `offset_s`
	* Описание: Операции смещения насыщенности через запятую.
	* Допустимые значения: `stringCommaSeparated`
	* Значение по умолчанию: `'+0'`
	
* `offset_s[i]`
	* Описание: Смещение насыщенности в процентах (`[-100; +100]`).
	* Допустимые значения: `string`
	* **Обязателен**
	
* `offset_l`
	* Описание: Операции смещения яркости через запятую.
	* Допустимые значения: `stringCommaSeparated`
	* Значение по умолчанию: `'+0'`
	
* `offset_l[i]`
	* Описание: Смещение яркости в процентах (`[-100; +100]`).
	* Допустимые значения: `string`
	* **Обязателен**
	
* `offset_a`
	* Описание: Операции смещения альфа-канала через запятую.
	* Допустимые значения: `stringCommaSeparated`
	* Значение по умолчанию: `'+0'`
	
* `offset_a[i]`
	* Описание: Смещение альфа-канала в процентах (`[-100; +100]`).
	* Допустимые значения: `string`
	* **Обязателен**


### Вывод результата

* `result_outputFormat`
	* Описание: В каком формате возвращать цвет?  
		Значение регистронезависимо.
	* Допустимые значения:
		* `'hex'`
		* `'hsl'`
		* `'rgb'`
	* Значение по умолчанию: `'hsl'`
	
* `result_tpl`
	* Описание: Чанк, через который выводить (если нужно).  
		Доступные плейсхолдеры:
		* `[+ddResult+]` — полная строка цвета
		* `[+ddH+]` — цветовой тон
		* `[+ddS+]` — насыщенность
		* `[+ddL+]` — яркость
		* `[+ddA+]` — альфа-канал
		* `[+ddIsDark+]` — является ли цвет тёмным (`0` || `1`)
	* Допустимые значения:
		* `stringChunkName`
		* `string` — передавать код напрямую без чанка можно начиная значение с `@CODE:`
	* Значение по умолчанию: —
	
* `result_tpl_placeholders`
	* Описание:
		Дополнительные данные, которые будут переданы в чанк `result_tpl`.  
		Вложенные объекты и массивы также поддерживаются:
		* `{"someOne": "1", "someTwo": "test" }` => `[+someOne+], [+someTwo+]`.
		* `{"some": {"a": "one", "b": "two"} }` => `[+some.a+]`, `[+some.b+]`.
		* `{"some": ["one", "two"] }` => `[+some.0+]`, `[+some.1+]`.
	* Допустимые значения:
		* `stringJsonObject` — в виде [JSON](https://ru.wikipedia.org/wiki/JSON)
		* `stringHjsonObject` — в виде [HJSON](https://hjson.github.io/)
		* `stringQueryFormatted` — в виде [Query string](https://en.wikipedia.org/wiki/Query_string)
		* Также может быть задан, как нативный PHP объект или массив (например, для вызовов через `$modx->runSnippet`).
			* `arrayAssociative`
			* `object`
	* Значение по умолчанию: —


## Примеры


### Установить чёрный или белый цвет шрифта в зависимости от цвета фона

Нам нужны чёрные тексты на светлых фонах и наоборот.

Зададим цвет фона в параметре сниппета `inputColor`:

```
color: [[ddColorTools?
	&inputColor=`#007cc3`
	&result_tpl=`blackOrWhiteColor`
]];
```

Код чанка `blackOrWhiteColor`:

```
hsl(0, 0%, [[ddIf?
	&operand1=`[+ddIsDark+]`
	&operator=`bool`
	&trueChunk=`100`
	&falseChunk=`0`
]]%)
```


### Запустить сниппет через `\DDTools\Snippet::runSnippet` без DB и eval

```php
//Подключение (MODX)EvolutionCMS.libraries.ddTools
require_once(
	$modx->getConfig('base_path') .
	'assets/libs/ddTools/modx.ddtools.class.php'
);

//Запуск (MODX)EvolutionCMS.snippets.ddColorTools
\DDTools\Snippet::runSnippet([
	'name' => 'ddColorTools',
	'params' => [
		'inputColor' => '#000000',
		'result_tpl' => 'colorTpl'
	]
]);
```


## Ссылки

* [Home page](https://code.divandesign.ru/modx/ddcolortools)
* [Telegram chat](https://t.me/dd_code)
* [Packagist](https://packagist.org/packages/dd/evolutioncms-snippets-ddcolortools)
* [GitHub](https://github.com/DivanDesign/EvolutionCMS.snippets.ddColorTools)


<link rel="stylesheet" type="text/css" href="https://raw.githack.com/DivanDesign/CSS.ddMarkdown/master/style.min.css" />