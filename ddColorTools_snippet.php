<?php
/**
 * ddColorTools
 * @version 3.0 (2020-05-08)
 * 
 * @see README.md
 * 
 * @link https://code.divandesign.biz/modx/ddcolortools
 * 
 * @copyright 2011–2020 DD Group {@link https://DivanDesign.biz }
 */

//Include (MODX)EvolutionCMS.libraries.ddTools
require_once(
	$modx->getConfig('base_path') .
	'assets/libs/ddTools/modx.ddtools.class.php'
);

//The snippet must return an empty string even if result is absent
$snippetResult = '';
//Если задано имя поля, которое необходимо получить
if(isset($inputColor_docField)){
	$inputColor = $modx->runSnippet(
		'ddGetDocumentField',
		[
			'dataProviderParams' => '{
				"resourceId": "' .
					$inputColor_docId .
				'",
				"resourceFields": "' .
					$inputColor_docField .
				'",
			}'
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
		strtolower($result_outputFormat) :
		'hsl'
	;
	
	$hslMax = [
		'H' => 360,
		'S' => 100,
		'L' => 100
	];
	
	if(!function_exists('ddHEXtoHSL')){
		function ddHEXtoHSL($hex){
			//Получаем цвета в 10чной системе
			$red = hexdec(substr(
				$hex,
				0,
				2
			));
			$green = hexdec(substr(
				$hex,
				2,
				2
			));
			$blue = hexdec(substr(
				$hex,
				4,
				2
			));
			
			//Находим максимальное и минимальное значения
			$max = max(
				$red,
				$green,
				$blue
			);
			$min = min(
				$red,
				$green,
				$blue
			);
			
			$resultHsl = [];
			//Вычисляем яркость (от 0 до 100)
			$resultHsl['L'] = round(($max + $min) / 2 * 100 / 255);
			
			//Если цвет серый
			if($max == $min){
				$resultHsl['S'] = 0;
				$resultHsl['H'] = 0;
			}else{
				//Вычисляем насыщенность
				$resultHsl['S'] = round(
					(
						$resultHsl['L'] > 50 ?
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
				$tmpR =
					($max - $red) /
					($max - $min)
				;
				$tmpG =
					($max - $green) /
					($max - $min)
				;
				$tmpL =
					($max - $blue) /
					($max - $min)
				;
				
				if($red == $max){
					$hue = $tmpL - $tmpG;
				}elseif($green == $max){
					$hue = 2 + $tmpR - $tmpL;
				}elseif($blue == $max){
					$hue = 4 + $tmpG - $tmpR;
				}
				
				$resultHsl['H'] =
					(
						round($hue * 60) +
						360
					) %
					360
				;
			}
			
			return $resultHsl;
		}
	}
	
	if(!function_exists('ddHSLtoHEX')){
		function ddHSLtoHEX($hsl){
			$saturation = $hsl['S'];
			$lightness = $hsl['L'];
			
			$rgb = [];
			
			//Если цвет серый
			if($saturation == 0){
				$rgb['R'] = $lightness;
				$rgb['G'] = $lightness;
				$rgb['B'] = $lightness;
			}else{
				$hue = ($hsl['H'] + 360) % 360;
				$hue2 = floor($hue / 60);
				
				$dif =
					($hue % 60) /
					60
				;
				$mid1 =
					$lightness *
					(
						100 -
						$saturation * $dif
					) /
					100
				;
				$mid2 =
					$lightness *
					(
						100 -
						$saturation * (1 - $dif)
					) /
					100
				;
				$min =
					$lightness *
					(100 - $saturation) /
					100
				;
				
				if($hue2 == 0){
					$rgb['R'] = $lightness;
					$rgb['G'] = $mid2;
					$rgb['B'] = $min;
				}elseif($hue2 == 1){
					$rgb['R'] = $mid1;
					$rgb['G'] = $lightness;
					$rgb['B'] = $min;
				}elseif($hue2 == 2){
					$rgb['R'] = $min;
					$rgb['G'] = $lightness;
					$rgb['B'] = $mid2;
				}elseif($hue2 == 3){
					$rgb['R'] = $min;
					$rgb['G'] = $mid1;
					$rgb['B'] = $lightness;
				}elseif($hue2 == 4){
					$rgb['R'] = $mid2;
					$rgb['G'] = $min;
					$rgb['B'] = $lightness;
				}else{
					$rgb['R'] = $lightness;
					$rgb['G'] = $min;
					$rgb['B'] = $mid1;
				}
			}
			
			//Обходим массив и преобразовываем все значения в hex (предварительно переводим из системы счисления от 0 до 100 в от 0 до 255)
			return array_map(
				create_function(
					'$a',
					'
						$res = dechex(round($a*255/100));
						//Если не хватает ноля, дописываем
						return (strlen($res) < 2) ? "0".$res : $res;
					'
				),
				$rgb
			);
		}
	}
	
	//Case-insensitive
	$inputColor = strtolower($inputColor);
	
	//If input color set as HSL
	if (
		strpos(
			$inputColor,
			'hsl'
		) !== false
	){
		//Remove unwanted chars
		$inputColor = str_replace(
			[
				'hsl',
				'(',
				')',
				//Space
				' ',
				//Tab
				'	'
			],
			'',
			$inputColor
		);
		
		$inputColor = explode(
			',',
			$inputColor
		);
		
		$inputColorHsl = [
			'H' => $inputColor[0],
			'S' => $inputColor[1],
			'L' => $inputColor[2]
		];
	//AS RGB
	}else{
		//Удалим из цвета символ '#'
		$inputColor = str_replace(
			'#',
			'',
			$inputColor
		);
		
		//Преобразуем цвет в HSL
		$inputColorHsl = ddHEXtoHSL($inputColor);
	}
	
	foreach(
		$inputColorHsl as
		$key =>
		$val
	){
		foreach (
			$hslRange[$key] as
			$operation
		){
			$operation_sign = preg_replace(
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
				$operation_sign,
				'+'
			) !== false){
				$inputColorHsl[$key] += $operation;
				//Если нужно отнять
			}elseif(strpos(
				$operation_sign,
				'-'
			) !== false){
				$inputColorHsl[$key] -= $operation;
			//Если нужно приравнять (если есть хоть какое-то число)
			}elseif(strlen($operation) > 0){
				$inputColorHsl[$key] = $operation;
			}
			
			//Если нужно задать максимальное, либо минимальное значение
			if(strpos(
				$operation_sign,
				'abs'
			) !== false){
				//Если меньше 50% — 0, в противном случае — максимальное значение
				$inputColorHsl[$key] =
					$inputColorHsl[$key] < ($hslMax[$key] / 2) ?
					0 :
					$hslMax[$key]
				;
			}
			
			//Если нужно инвертировать
			if(strpos(
				$operation_sign,
				'r'
			) !== false){
				$inputColorHsl[$key] =
					$hslMax[$key] +
					(-1 * $inputColorHsl[$key])
				;
			}
			
			//Обрабатываем слишком маленькие значения
			if($inputColorHsl[$key] < 0){
				$inputColorHsl[$key] =
					$hslMax[$key] +
					$inputColorHsl[$key]
				;
			}
		}
	}
	
	//Обрабатываем слишком большие значения
	if($inputColorHsl['H'] > $hslMax['H']){
		$inputColorHsl['H'] = $inputColorHsl['H'] - $hslMax['H'];
	}
	if($inputColorHsl['S'] > $hslMax['S']){
		$inputColorHsl['S'] = $hslMax['S'];
	}
	if($inputColorHsl['L'] > $hslMax['L']){
		$inputColorHsl['L'] = $hslMax['L'];
	}
	
	switch($result_outputFormat){
		case 'hsl':
			$snippetResult =
				'hsl(' .
				$inputColorHsl['H'] .
				',' .
				$inputColorHsl['S'] .
				'%,' .
				$inputColorHsl['L'] .
				'%)'
			;
		break;
		
		case 'hex':
			$snippetResult = implode(
				'',
				ddHSLtoHEX($inputColorHsl)
			);
		break;
	}
	
	if (!empty($result_tpl)){
		$snippetResult = [
			'ddResult' => $snippetResult,
			'ddH' => $inputColorHsl['H'],
			'ddS' => $inputColorHsl['S'],
			'ddL' => $inputColorHsl['L']
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