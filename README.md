# (MODX)EvolutionCMS.snippets.ddColorTools

Converts the color to match the offset in tone, brightness, or saturation.


## Requires

* PHP >= 5.6
* [(MODX)EvolutionCMS.libraries.ddTools](https://code.divandesign.biz/modx/ddtools) >= 0.60
* [(MODX)EvolutionCMS.snippets.ddGetDocumentField](https://code.divandesign.biz/modx/ddgetdocumentfield) >= 2.11.1


## Installation


### Manually


#### 1. Elements → Snippets: Create a new snippet with the following data

1. Snippet name: `ddColorTools`.
2. Description: `<b>3.1</b> Converts the color to match the offset in tone, brightness, or saturation.`.
3. Category: `Core`.
4. Parse DocBlock: `no`.
5. Snippet code (php): Insert content of the `ddColorTools_snippet.php` file from the archive.


#### 2. Elements → Manage Files

1. Create a new folder `assets/snippets/ddColorTools/`.
2. Extract the archive to the folder (except `ddColorTools_snippet.php`).


### Using [(MODX)EvolutionCMS.libraries.ddInstaller](https://github.com/DivanDesign/EvolutionCMS.libraries.ddInstaller)

Just run the following PHP code in your sources or [Console](https://github.com/vanchelo/MODX-Evolution-Ajax-Console):

```php
//Include (MODX)EvolutionCMS.libraries.ddInstaller
require_once(
	$modx->getConfig('base_path') .
	'assets/libs/ddInstaller/require.php'
);

//Install (MODX)EvolutionCMS.snippets.ddColorTools
\DDInstaller::install([
	'url' => 'https://github.com/DivanDesign/EvolutionCMS.snippets.ddColorTools',
	'type' => 'snippet'
]);
```

* If `ddColorTools` is not exist on your site, `ddInstaller` will just install it.
* If `ddColorTools` is already exist on your site, `ddInstaller` will check it version and update it if needed.


## Parameters description


### Input color

* `inputColor`
	* Desctription: Input color as HEX, HSL or HSB/HSV, with or without alpha-channel.  
		Case-insensitive.    
		Valid format examples:
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


### Color modification

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
	
* `offset_a`
	* Desctription: Operations of the alpha-channel offset separated by commas.
	* Valid values: `stringCommaSeparated`
	* Default value: `'+0'`
	
* `offset_a[i]`
	* Desctription: Offset of the alpha-channel in persents (`[-100; +100]`).
	* Valid values: `string`
	* **Required**


### Output

* `result_outputFormat`
	* Desctription: Output color format.  
		Case-insensitive.
	* Valid values:
		* `'hex'`
		* `'hsl'`
		* `'rgb'`
	* Default value: `'hsl'`
	
* `result_tpl`
	* Desctription: Chunk to parse result.  
		Available placeholders:
		* `[+ddResult+]` — full color string
		* `[+ddH+]` — hue
		* `[+ddS+]` — saturation
		* `[+ddL+]` — lightness
		* `[+ddA+]` — alpha-channel
		* `[+ddIsDark+]` — is color dark (`0` || `1`)?
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
		* `stringHjsonObject` — as [HJSON](https://hjson.github.io/)
		* `stringQueryFormatted` — as [Query string](https://en.wikipedia.org/wiki/Query_string)
		* It can also be set as a native PHP object or array (e. g. for calls through `$modx->runSnippet`):
			* `arrayAssociative`
			* `object`
	* Default value: —


## Examples


### Set black or white font color depending on background color

We need black texts in light backgrounds and vice versa.

Let's pass background color as `inputColor` to the snippet:

```
color: [[ddColorTools?
	&inputColor=`#007cc3`
	&result_tpl=`blackOrWhiteColor`
]];
```

Code of the `blackOrWhiteColor` chunk:

```
hsl(0, 0%, [[ddIf?
	&operand1=`[+ddIsDark+]`
	&operator=`bool`
	&trueChunk=`100`
	&falseChunk=`0`
]]%)
```


### Run the snippet through `\DDTools\Snippet::runSnippet` without DB and eval

```php
//Include (MODX)EvolutionCMS.libraries.ddTools
require_once(
	$modx->getConfig('base_path') .
	'assets/libs/ddTools/modx.ddtools.class.php'
);

//Run (MODX)EvolutionCMS.snippets.ddColorTools
\DDTools\Snippet::runSnippet([
	'name' => 'ddColorTools',
	'params' => [
		'inputColor' => '#000000',
		'result_tpl' => 'colorTpl'
	]
]);
```


## Links

* [Home page](https://code.divandesign.biz/modx/ddcolortools)
* [Telegram chat](https://t.me/dd_code)
* [Packagist](https://packagist.org/packages/dd/evolutioncms-snippets-ddcolortools)
* [GitHub](https://github.com/DivanDesign/EvolutionCMS.snippets.ddColorTools)


<link rel="stylesheet" type="text/css" href="https://DivanDesign.ru/assets/files/ddMarkdown.css" />