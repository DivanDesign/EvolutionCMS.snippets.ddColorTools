# (MODX)EvolutionCMS.snippets.ddColorTools

Преобразует цвет в соответствии со смещением по тону, яркости или насыщенности.


## Использует

* PHP >= 5.6
* [(MODX)EvolutionCMS.libraries.ddTools](https://code.divandesign.biz/modx/ddtools) >= 0.32
* [(MODX)EvolutionCMS.snippets.ddGetDocumentField](https://code.divandesign.biz/modx/ddgetdocumentfield) >= 2.10.1


## Документация


### Установка

Элементы → Сниппеты: Создайте новый сниппет со следующими параметрами:

1. Название сниппета: `ddColorTools`.
2. Описание: `<b>3.0</b> Преобразует цвет в соответствии со смещением по тону, яркости или насыщенности.`.
3. Категория: `Core`.
4. Анализировать DocBlock: `no`.
5. Код сниппета (php): Вставьте содержимое файла `ddColorTools_snippet.php` из архива.


### Описание параметров


#### Исходный цвет

* `inputColor`
	* Описание: Исходный цвет в HEX или HSL.  
		Значение регистронезависимо.  
		Примеры валидных значений:
		* `ffffff`
		* `#FFFFFF`
		* `hsl(0, 0%, 100%)`
		* `HSL(0, 0, 100)`
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


#### Модификация цвета

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


#### Вывод результата

* `result_outputFormat`
	* Описание: В каком формате возвращать цвет?  
		Значение регистронезависимо.
	* Допустимые значения:
		* `'hex'`
		* `'hsl'`
	* Значение по умолчанию: `'hsl'`
	
* `result_tpl`
	* Описание: Чанк, через который выводить (если нужно).  
		Доступные плейсхолдеры:
		* `[+ddResult+]` — полная строка цвета
		* `[+ddH+]` — цветовой тон
		* `[+ddS+]` — насыщенность
		* `[+ddL+]` — яркость
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
		* `stringJsonObject` — as [JSON](https://en.wikipedia.org/wiki/JSON)
		* `stringQueryFormated` — as [Query string](https://en.wikipedia.org/wiki/Query_string)
	* Значение по умолчанию: —


## [Home page →](https://code.divandesign.biz/modx/ddcolortools)


<link rel="stylesheet" type="text/css" href="https://DivanDesign.ru/assets/files/ddMarkdown.css" />