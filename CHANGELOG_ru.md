# (MODX)EvolutionCMS.snippets.ddColorTools changelog


## Версия 3.2.1 (2024-01-20)
* \* Outputter:
	* \* Исправлен неправильный вывод `hsla`/`rgba`.
	* \* Альфа-канал не возвращается, если он равен `100%`.


## Версия 3.2 (2024-01-20)
* \+ Параметры → `inputColor`: Поддерживает занчения с альфа-каналом.
	* \+ `offset_a`: Новый параметр. Позволяет модифицировать альфа-канал.
	* \+ `result_tpl` → Плейсхолдеры → `[+ddA+]`: Новый плейсхолдер.
* \* `\ddTools::getTpl` используется вместо `$modx->getTpl`.
* \* Внимание! Требуется (MODX)EvolutionCMS.libraries.ddTools >= 0.60 is.


## Версия 3.1 (2023-03-10)
* \* Внимание! Требуется (MODX)EvolutionCMS.libraries.ddTools >= 0.57.
* \* Внимание! Требуется (MODX)EvolutionCMS.snippets.ddGetDocumentField >= 2.11.1.
* \+ Параметры:
	* \+ `inputColor`:
		* \+ Также может быть задан, как HSB/HSV.
		* \+ Поддерживается символ `%` в форматах HSL или HSB/HSV.
	* \+ `result_outputFormat` → Допустимые значения → `rgb`: Новый формат вывода.
	* \+ `result_tpl` → Плейсхолдеры → `[+ddIsDark+]`: Новый плейсхолдер. Равен `1` если цвет тёмный или `0` в противном случае (см. README → Примеры).
	* \+ `result_tpl_placeholders`: Также может быть задан, как [HJSON](https://hjson.github.io/) или как нативный PHP объект/массив.
* \+ Запустить сниппет без DB и eval можно через `\DDTools\Snippet::runSnippet` (см. примеры в README → Примеры).
* \* `\DDTools\Snippet::runSnippet` используется вместо `$modx->runSnippet` для запуска (MODX)EvolutionCMS.snippets.ddGetDocumentField без DB и eval.
* \* Исправлена ошибка HSL ≠ HSB, возникшая в 55685f6b2ab6be5806bcd6c4c1f9c382ac5a328d.
* \* README:
	* \+ Установка → Используя (MODX)EvolutionCMS.libraries.ddInstaller.
	* \+ Ссылки.
* \+ Composer.json:
	* \+ `homepage`.
	* \+ `support`.
	* \+ `authors`.


## Версия 3.0 (2020-05-08)
* \* Внимание! Требуется (MODX)EvolutionCMS.snippets.ddGetDocumentField >= 2.10.1.
* \* Внимание! Требуется (MODX)EvolutionCMS.libraries.ddTools >= 0.32.
* \* Внимание! Обратная совместимость нарушена:
	* \* `HSB` переименован в `HSL` внутри сниппета.
	* \* Параметры:
		* \* Следующие переименованы:
			* \* `offset_b` → `offset_l`
			* \* `outputFormat` → `result_outputFormat`
		* \* `result_outputFormat`:
			* \* Если равен `hsl` — будет возвращёно полное представление цвета (`hsl(0, 0%, 0%)`).
			* \* Значение по умолчанию: Равно `hsl` вместо `hex`.
* \+ Параметры → `inputColor`: Добавлена возможность задавать в формате HSL.
* \+ Параметры → `offset_` (все): Добавлена поддержка множественных операций.
* \+ Параметры → `result_outputFormat`: Значение регистронезависимо.
* \+ Параметры → `result_tpl`.
* \+ Параметры → `result_tpl_placeholders`.
* \* Сниппет вернёт пустую строку в случае если результ пуст.
* \* Рефакторинг.
* \+ Composer.json.
* \+ README.
* \+ README_ru.
* \+ CHANGELOG.
* \+ CHANGELOG_ru.


## Версия 2.0 (2017-05-22)
* \+ Начальный кэммит.


<link rel="stylesheet" type="text/css" href="https://raw.githack.com/DivanDesign/CSS.ddMarkdown/master/style.min.css" />
<style>ul{list-style:none;}</style>