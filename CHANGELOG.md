# (MODX)EvolutionCMS.snippets.ddColorTools changelog


## Version 3.0 (2020-05-08)
* \* Attention! (MODX)EvolutionCMS.snippets.ddGetDocumentField >= 2.10.1 is required.
* \* Attention! (MODX)EvolutionCMS.libraries.ddTools >= 0.32 is required.
* \* Attention! Backward compatibility is broken:
	* \* `HSB` renamed as `HSL` inside the snippet.
	* \* Parameters:
		* \* The following were renamed:
			* \* `offset_b` → `offset_l`
			* \* `outputFormat` → `result_outputFormat`
		* \* `result_outputFormat`:
			* \* If equal to `hsl` then full color string will be returned (`hsl(0, 0%, 0%)`).
			* \* Default value: Is equal to `hsl` instead of `hex`.
* \+ Parameters → `inputColor`: Added the ability to set as HSL.
* \+ Parameters → `offset_` (all): Supports multiple operations.
* \+ Parameters → `result_outputFormat`: Case-insensitive.
* \+ Parameters → `result_tpl`.
* \+ Parameters → `result_tpl_placeholders`.
* \* The snippet will return an empty string even if result is absent.
* \* Refactoring.
* \+ Composer.json.
* \+ README.
* \+ README_ru.
* \+ CHANGELOG.
* \+ CHANGELOG_ru.


## Version 2.0 (2017-05-22)
* \+ Initial commit.


<link rel="stylesheet" type="text/css" href="https://DivanDesign.ru/assets/files/ddMarkdown.css" />
<style>ul{list-style:none;}</style>