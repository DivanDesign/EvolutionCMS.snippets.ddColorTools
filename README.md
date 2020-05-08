# (MODX)EvolutionCMS.snippets.ddColorTools

Converts the color to match the offset in tone, brightness, or saturation.


## Requires

* PHP >= 5.6
* [(MODX)EvolutionCMS.libraries.ddTools](https://code.divandesign.biz/modx/ddtools) >= 0.32
* [(MODX)EvolutionCMS.snippets.ddGetDocumentField](https://code.divandesign.biz/modx/ddgetdocumentfield) >= 2.10.1


## Documentation


### Installation

Elements → Snippets: Create a new snippet with the following data:

1. Snippet name: `ddColorTools`.
2. Description: `<b>3.0</b> Converts the color to match the offset in tone, brightness, or saturation.`.
3. Category: `Core`.
4. Parse DocBlock: `no`.
5. Snippet code (php): Insert content of the `ddColorTools_snippet.php` file from the archive.


### Parameters description


#### Input color

* `inputColor`
	* Desctription: Input color as HEX or HSL.  
		Case-insensitive.    
		Valid format examples:
		* `ffffff`
		* `#FFFFFF`
		* `hsl(0, 0%, 100%)`
		* `HSL(0, 0, 100)`
	* Valid values: `string`
	* **Required**
	
* `inputColor_docField`
	* Desctription: The name of the document field / TV which value is required to get.  
		If the parameter is passed then the input string will be taken from the field / TV and `inputColor` will be ignored.
	* Valid values: `string`
	* Default value: —
	
* `inputColor_docId`
	* Desctription: ID of the document which field/TV value is required to get.  
		`inputColor_docId` equals the current document id since `inputString_docId` is unset.
	* Valid values: `integer`
	* Default value: —


#### Color modification

All parameters can contain the following special operators:
1. `+` (e. g. `+10`) — plus
2. `-` (e. g. `-10`) — minus
3. `abs` — round to max or min value
4. `r` — invert
5. without operator (e. g. `10`) — just set equal to

* `offset_h`
	* Desctription: Operations of the hue offset separated by commas.
	* Valid values: `stringCommaSeparated`
	* Default value: `'+0'`
	
* `offset_h[i]`
	* Desctription: Offset of the hue in degrees (`[-360; +360]`).
	* Valid values: `string`
	* **Required**
	
* `offset_s`
	* Desctription: Operations of the saturation offset separated by commas.
	* Valid values: `stringCommaSeparated`
	* Default value: `'+0'`
	
* `offset_s[i]`
	* Desctription: Offset of the saturation in persents (`[-100; +100]`).
	* Valid values: `string`
	* **Required**
	
* `offset_l`
	* Desctription: Operations of the lightness offset separated by commas.
	* Valid values: `stringCommaSeparated`
	* Default value: `'+0'`
	
* `offset_l[i]`
	* Desctription: Offset of the lightness in persents (`[-100; +100]`).
	* Valid values: `string`
	* **Required**


#### Output

* `result_outputFormat`
	* Desctription: Output color format.  
		Case-insensitive.
	* Valid values:
		* `'hex'`
		* `'hsl'`
	* Default value: `'hsl'`
	
* `result_tpl`
	* Desctription: Chunk to parse result.  
		Available placeholders:
		* `[+ddResult+]` — full color string
		* `[+ddH+]` — hue
		* `[+ddS+]` — saturation
		* `[+ddL+]` — lightness
	* Valid values:
		* `stringChunkName`
		* `string` — use inline templates starting with `@CODE:`
	* Default value: —
	
* `result_tpl_placeholders`
	* Desctription:
		Additional data has to be passed into the `result_tpl`.  
		Nested objects and arrays are supported too:
		* `{"someOne": "1", "someTwo": "test" }` => `[+someOne+], [+someTwo+]`.
		* `{"some": {"a": "one", "b": "two"} }` => `[+some.a+]`, `[+some.b+]`.
		* `{"some": ["one", "two"] }` => `[+some.0+]`, `[+some.1+]`.
	* Valid values:
		* `stringJsonObject` — as [JSON](https://en.wikipedia.org/wiki/JSON)
		* `stringQueryFormated` — as [Query string](https://en.wikipedia.org/wiki/Query_string)
	* Default value: —


## [Home page →](https://code.divandesign.biz/modx/ddcolortools)


<link rel="stylesheet" type="text/css" href="https://DivanDesign.ru/assets/files/ddMarkdown.css" />