# (MODX)EvolutionCMS.snippets.ddColorTools changelog


## Version 3.2.1 (2024-01-20)
* \* Outputter:
	* \* Incorrect output of `hsla`/`rgba` have been fixed.
	* \* Alpha-channel is not outputted if it is equal to `100%`.


## Version 3.2 (2024-01-20)
* \+ Parameters → `inputColor`: Supports values with alpha-channel.
	* \+ `offset_a`: The new parameter. Allows you to modify alpha-channel.
	* \+ `result_tpl` → Placeholders → `[+ddA+]`: The new placeholder.
* \* `\ddTools::getTpl` is used instead of `$modx->getTpl`.
* \* Attention! (MODX)EvolutionCMS.libraries.ddTools >= 0.60 is required.


## Version 3.1 (2023-03-10)
* \* Attention! (MODX)EvolutionCMS.libraries.ddTools >= 0.57 is required.
* \* Attention! (MODX)EvolutionCMS.snippets.ddGetDocumentField >= 2.11.1 is required.
* \+ Parameters:
	* \+ `inputColor`:
		* \+ Can also be set as HSB/HSV.
		* \+ Supports the `%` sign in the HSL or HSB/HSV formats.
	* \+ `result_outputFormat` → Valid values → `rgb`: The new output format.
	* \+ `result_tpl` → Placeholders → `[+ddIsDark+]`: The new placeholder. It equals `1` if input color is dark or `0` otherwise (see README → Examples).
	* \+ `result_tpl_placeholders`: Can also be set as [HJSON](https://hjson.github.io/) or as a native PHP object or array.
* \+ You can just call `\DDTools\Snippet::runSnippet` to run the snippet without DB and eval (see README → Examples).
* \* `\DDTools\Snippet::runSnippet` is used instead of `$modx->runSnippet` to run (MODX)EvolutionCMS.snippets.ddGetDocumentField without DB and eval.
* \* The HSL ≠ HSB error that occurred in 55685f6b2ab6be5806bcd6c4c1f9c382ac5a328d has been fiexed.
* \* README:
	* \+ Installation → Using (MODX)EvolutionCMS.libraries.ddInstaller.
	* \+ Links.
* \+ Composer.json:
	* \+ `homepage`.
	* \+ `support`.
	* \+ `authors`.


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


<link rel="stylesheet" type="text/css" href="https://raw.githack.com/DivanDesign/CSS.ddMarkdown/master/style.min.css" />
<style>ul{list-style:none;}</style>