# (MODX)EvolutionCMS.snippets.ddColorTools changelog


## Version 3.0 (2020-05-08)
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


<link rel="stylesheet" type="text/css" href="https://DivanDesign.ru/assets/files/ddMarkdown.css" />
<style>ul{list-style:none;}</style>