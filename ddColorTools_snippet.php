<?php
/**
 * ddColorTools
 * @version 2.0 (2017-05-22)
 * 
 * @desc Преобразует цвет в соответствии со смещением по тону, яркости или насыщенности.
 * 
 * @uses PHP >= 5.6.
 * @uses [(MODX)EvolutionCMS.libraries.ddTools](https://code.divandesign.biz/modx/ddtools) >= 0.28
 * @uses [(MODX)EvolutionCMS.snippets.ddGetDocumentField](https://code.divandesign.biz/modx/ddgetdocumentfield) >= 2.8
 * 
 * @param $inputColor {string} — Цвет в HEX. @required
 * @example `ffffff`
 * @example `#ffffff`
 * @param $inputColor_docField {string} — Имя поля (в котором содержится цвет) которое необходимо получить. Default: —.
 * @param $inputColor_docId {integer} — ID документа, поле которого нужно получить. Default: —.
 * @param $offset_h {string_commaSeparated} — Операции смещения цветового тона через запятую. Default: `'+0'`.
 * @param $offset_h[i] {string} — Смещение цветового тона в градусах [-360;+360]. `+` — прибавить, `-` — отнять, без знака — задать, `abs` — округлить до макс. или мин. значения, `r` — инвертировать. @required
 * @param $offset_s {string_commaSeparated} — Операции смещения насыщенности через запятую. Default: `'+0'`.
 * @param $offset_s[i] {string_commaSeparated} — Смещение насыщенности в процентах [-100;+100]. `+` — прибавить, `-` — отнять, без знака — задать, `abs` — округлить до макс. или мин. значения, `r` — инвертировать. @required
 * @param $offset_l {string_commaSeparated} — Операции смещения яркости через запятую. Default: `'+0'`.
 * @param $offset_l[i] {string_commaSeparated} — Смещение яркости в процентах [-100;+100]. `+` — прибавить, `-` — отнять, без знака — задать, `abs` — округлить до макс. или мин. значения, `r` — инвертировать. @required
 * @param $result_outputFormat {'hex'|'hsl'} — Какой формат цвета возвращать. Default: `'hex'`.
 * @param $result_tpl {string_chunkName|string} — Chunk to parse result (chunk name or code via `@CODE:` prefix). Availiable placeholders: `[+ddResult+]`, `[+ddH+]`, `[+ddS+]`, `[+ddL+]`. Default: ``.
 * @param $result_tpl_placeholders {stirng_json|string_queryFormated} — Additional data as [JSON](https://en.wikipedia.org/wiki/JSON) or [Query string](https://en.wikipedia.org/wiki/Query_string) has to be passed into `result_tpl`. Default: ``.
 * @example `{"pladeholder1": "value1", "pagetitle": "My awesome pagetitle!"}`
 * @example `pladeholder1=value1&pagetitle=My awesome pagetitle!`
 * 
 * @copyright 2011–2017 DivanDesign {@link http://www.DivanDesign.biz }
 */

//Include (MODX)EvolutionCMS.libraries.ddTools
require_once(
	$modx->getConfig('base_path') .
	'assets/libs/ddTools/modx.ddtools.class.php'
);

//Для обратной совместимости
extract(\ddTools::verifyRenamedParams(
	$params,
	[
		'result_outputFormat' => 'outputFormat'
	]
));

//The snippet must return an empty string even if result is absent
$snippetResult = '';

//Если задано имя поля, которое необходимо получить
if(isset($inputColor_docField)){
	$inputColor = $modx->runSnippet(
		'ddGetDocumentField',
		[
			'docId' => $inputColor_docId,
			'docField' => $inputColor_docField
		]
	);
}

if(isset($inputColor)){
	$hslRange = [
		'H' =>
			isset($offset_h) ?
			explode(
				',',
				$offset_h
			) :
			['+0']
		,
		'S' =>
			isset($offset_s) ?
			explode(
				',',
				$offset_s
			) :
			['+0']
		,
		'L' =>
			isset($offset_l) ?
			explode(
				',',
				$offset_l
			) :
			['+0']
	];
	
	$result_outputFormat =
		isset($result_outputFormat) ?
		$result_outputFormat :
		'hex'
	;
	
	$hslMax = [
		'H' => 360,
		'S' => 100,
		'L' => 100
	];
	
	//Удалим из цвета символ '#'
	$inputColor = str_replace(
		'#',
		'',
		$inputColor
	);
	
	if(!function_exists('ddHEXtoHSL')){
		function ddHEXtoHSL($hex){
			//Получаем цвета в 10чной системе
			$red = hexdec(substr(
				$hex,
				0,
				2
			));
			$gre = hexdec(substr(
				$hex,
				2,
				2
			));
			$blu = hexdec(substr(
				$hex,
				4,
				2
			));
			
			//Находим максимальное и минимальное значения
			$max = max(
				$red,
				$gre,
				$blu
			);
			$min = min(
				$red,
				$gre,
				$blu
			);
			
			$hsl = [];
			//Вычисляем яркость (от 0 до 100)
			$hsl['L'] = round(($max + $min) / 2 * 100 / 255);
			
			//Если цвет серый
			if($max == $min){
				$hsl['S'] = 0;
				$hsl['H'] = 0;
			}else{
				//Вычисляем насыщенность
				$hsl['S'] = round(
					(
						$hsl['L'] > 50 ?
						(
							($max - $min) /
							(2 * 255 - $max - $min)
						) :
						(
							($max - $min) /
							($max + $min)
						)
					) *
					100
				);
				
				//Вычисляем тон
				$hue = 0;
				$tmpR = ($max - $red) / ($max - $min);
				$tmpG = ($max - $gre) / ($max - $min);
				$tmpL = ($max - $blu) / ($max - $min);
				
				if($red == $max){
					$hue = $tmpL - $tmpG;
				}else if($gre == $max){
					$hue = 2 + $tmpR - $tmpL;
				}else if($blu == $max){
					$hue = 4 + $tmpG - $tmpR;
				}
				
				$hsl['H'] = (round($hue * 60) + 360) % 360;
			}
			
			return $hsl;
		}
	}
	
	if(!function_exists('ddHSLtoHEX')){
		function ddHSLtoHEX($hsl){
			$sat = $hsl['S'];
			$bri = $hsl['L'];
			
			$rgb = [];
			
			//Если цвет серый
			if($sat == 0){
				$rgb['R'] = $bri;
				$rgb['G'] = $bri;
				$rgb['B'] = $bri;
			}else{
				$hue = ($hsl['H'] + 360) % 360;
				$hue2 = floor($hue / 60);
				
				$dif = ($hue % 60) / 60;
				$mid1 = $bri * (100 - $sat * $dif) / 100;
				$mid2 = $bri * (100 - $sat * (1 - $dif)) / 100;
				$min = $bri * (100 - $sat) / 100;
				
				if($hue2 == 0){
					$rgb['R'] = $bri;
					$rgb['G'] = $mid2;
					$rgb['B'] = $min;
				}else if($hue2 == 1){
					$rgb['R'] = $mid1;
					$rgb['G'] = $bri;
					$rgb['B'] = $min;
				}else if($hue2 == 2){
					$rgb['R'] = $min;
					$rgb['G'] = $bri;
					$rgb['B'] = $mid2;
				}else if($hue2 == 3){
					$rgb['R'] = $min;
					$rgb['G'] = $mid1;
					$rgb['B'] = $bri;
				}else if($hue2 == 4){
					$rgb['R'] = $mid2;
					$rgb['G'] = $min;
					$rgb['B'] = $bri;
				}else{
					$rgb['R'] = $bri;
					$rgb['G'] = $min;
					$rgb['B'] = $mid1;
				}
			}
			//Обходим массив и преобразовываем все значения в hex (предварительно переводим из системы счисления от 0 до 100 в от 0 до 255)
			return array_map(create_function('$a', '
				$res = dechex(round($a*255/100));
				//Если не хватает ноля, дописываем
				return (strlen($res) < 2) ? "0".$res : $res;
			'), $rgb);
		}
	}
	
	//Преобразуем цвет в HSL
	$hsl = ddHEXtoHSL($inputColor);
	
	foreach(
		$hsl as
		$key =>
		$val
	){
		foreach (
			$hslRange[$key] as
			$operation
		){
			$sim = preg_replace(
				'/\d/',
				'',
				$operation
			);
			$operation = preg_replace(
				'/\D/',
				'',
				$operation
			);
			
			//Если нужно прибавить
			if(strpos(
				$sim,
				'+'
			) !== false){
				$hsl[$key] += $operation;
				//Если нужно отнять
			}else if(strpos(
				$sim,
				'-'
			) !== false){
				$hsl[$key] -= $operation;
			//Если нужно приравнять (если есть хоть какое-то число)
			}else if(strlen($operation) > 0){
				$hsl[$key] = $operation;
			}
			
			//Если нужно задать максимальное, либо минимальное значение
			if(strpos(
				$sim,
				'abs'
			) !== false){
				//Если меньше 50% — 0, в противном случае — максимальное значение
				$hsl[$key] =
					$hsl[$key] < ($hslMax[$key] / 2) ?
					0 :
					$hslMax[$key]
				;
			}
			
			//Если нужно инвертировать
			if(strpos(
				$sim,
				'r'
			) !== false){
				$hsl[$key] = $hslMax[$key] + (-1 * $hsl[$key]);
			}
			
			//Обрабатываем слишком маленькие значения
			if($hsl[$key] < 0){
				$hsl[$key] = $hslMax[$key] + $hsl[$key];
			}
		}
	}
	
	//Обрабатываем слишком большие значения
	if($hsl['H'] > $hslMax['H']){
		$hsl['H'] = $hsl['H'] - $hslMax['H'];
	}
	if($hsl['S'] > $hslMax['S']){
		$hsl['S'] = $hslMax['S'];
	}
	if($hsl['L'] > $hslMax['L']){
		$hsl['L'] = $hslMax['L'];
	}
	
	switch($result_outputFormat){
		case 'hsl':
			$snippetResult =
				'hsl(' .
				$hsl['H'] .
				',' .
				$hsl['S'] .
				'%,' .
				$hsl['L'] .
				'%)'
			;
		break;
		
		case 'hex':
			$snippetResult = implode(
				'',
				ddHSLtoHEX($hsl)
			);
		break;
	}
	
	if (!empty($result_tpl)){
		$snippetResult = [
			'ddResult' => $snippetResult,
			'ddH' => $hsl['H'],
			'ddS' => $hsl['S'],
			'ddL' => $hsl['L']
		];
		
		//Если есть дополнительные данные
		if (
			isset($result_tpl_placeholders) &&
			trim($result_tpl_placeholders) != ''
		){
			$snippetResult = array_merge(
				$snippetResult,
				\ddTools::encodedStringToArray($result_tpl_placeholders)
			);
		}
		
		$snippetResult = \ddTools::parseText([
			'text' => $modx->getTpl($result_tpl),
			'data' => $snippetResult
		]);
	}
}

return $snippetResult;
?>