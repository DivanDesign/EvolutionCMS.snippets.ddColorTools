<?php
/**
 * ddColorTools
 * @version 2.0 (2017-05-22)
 *
 * @desc Преобразует цвет в соответствии со смещением по тону, яркости или насыщенности.
 *
 * @uses PHP >= 5.6.
 * @uses MODXEvo.snippet.ddGetDocumentField >= 2.4
 *
 * @param $inputColor {string} - Цвет в hex (например: ffffff). @required
 * @param $inputColor_docField {string} - Имя поля (в котором содержится цвет) которое необходимо получить. Default: —.
 * @param $inputColor_docId {integer} - ID документа, поле которого нужно получить. Default: —.
 * @param $offset_h {string} - Смещение цветового тона в градусах [-360;+360]. "+" - прибавить, "-" - отнять, без знака - задать, "abs" - округлить до макс. или мин. значения, "r" - инвертировать. Default: '+0'.
 * @param $offset_s {string} - Смещение насыщенности в процентах [-100;+100]. "+" - прибавить, "-" - отнять, без знака - задать, "abs" - округлить до макс. или мин. значения, "r" - инвертировать. Default: '+0'.
 * @param $offset_b {string} - Смещение яркости в процентах [-100;+100]. "+" - прибавить, "-" - отнять, без знака - задать, "abs" - округлить до макс. или мин. значения, "r" - инвертировать. Default: '+0'.
 * @param $outputFormat {'hex'|'hsb'} - Какой формат цвета возвращать. Default: 'hex'.
 *
 * @copyright 2011–2017 DivanDesign {@link http://www.DivanDesign.biz }
 */

//Если задано имя поля, которое необходимо получить
if(isset($inputColor_docField)){
	$inputColor = $modx->runSnippet('ddGetDocumentField', ['id' => $inputColor_docId, 'field' => $inputColor_docField]);
}

if(isset($inputColor)){
	$hsbRange = ['H' => isset($offset_h) ? $offset_h : '+0', 'S' => isset($offset_s) ? $offset_s : '+0', 'B' => isset($offset_b) ? $offset_b : '+0'];
	$outputFormat = isset($outputFormat) ? $outputFormat : 'hex';
	
	$hsbMax = ['H' => 360, 'S' => 100, 'B' => 100];
	
	//Удалим из цвета символ '#'
	$inputColor = str_replace('#', '', $inputColor);
	
	if(!function_exists('ddHEXtoHSB')){
		function ddHEXtoHSB($hex){
			//Получаем цвета в 10чной системе
			$red = hexdec(substr($hex, 0, 2));
			$gre = hexdec(substr($hex, 2, 2));
			$blu = hexdec(substr($hex, 4, 2));
			
			//Находим максимальное и минимальное значения
			$max = max($red, $gre, $blu);
			$min = min($red, $gre, $blu);
			
			$hsb = [];
			//Вычисляем яркость (от 0 до 100)
			$hsb['B'] = round($max * 100 / 255);
			
			//Если цвет серый
			if($max == $min){
				$hsb['S'] = 0;
				$hsb['H'] = 0;
			}else{
				//Вычисляем насыщенность
				$hsb['S'] = round(100 * ($max - $min) / $max);
				//Вычисляем тон
				$hue = 0;
				$tmpR = ($max - $red) / ($max - $min);
				$tmpG = ($max - $gre) / ($max - $min);
				$tmpB = ($max - $blu) / ($max - $min);
				
				if($red == $max){
					$hue = $tmpB - $tmpG;
				}else if($gre == $max){
					$hue = 2 + $tmpR - $tmpB;
				}else if($blu == $max){
					$hue = 4 + $tmpG - $tmpR;
				}
				$hsb['H'] = (round($hue * 60) + 360) % 360;
			}
			
			return $hsb;
		}
	}
	
	if(!function_exists('ddHSBtoHEX')){
		function ddHSBtoHEX($hsb){
			$sat = $hsb['S'];
			$bri = $hsb['B'];
			
			$rgb = [];
			
			//Если цвет серый
			if($sat == 0){
				$rgb['R'] = $bri;
				$rgb['G'] = $bri;
				$rgb['B'] = $bri;
			}else{
				$hue = ($hsb['H'] + 360) % 360;
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
	
	//Преобразуем цвет в HSB
	$hsb = ddHEXtoHSB($inputColor);
	
	foreach($hsb AS $key => $val){
		$sim = preg_replace('/\d/', '', $hsbRange[$key]);
		$hsbRange[$key] = preg_replace('/\D/', '', $hsbRange[$key]);
		
		//Если нужно прибавить
		if(strpos($sim, '+') !== false){
			$hsb[$key] += $hsbRange[$key];
			//Если нужно отнять
		}else if(strpos($sim, '-') !== false){
			$hsb[$key] -= $hsbRange[$key];
			//Если нужно приравнять (если есть хоть какое-то число)
		}else if(strlen($hsbRange[$key]) > 0){
			$hsb[$key] = $hsbRange[$key];
		}
		
		//Если нужно задать максимальное, либо минимальное значение
		if(strpos($sim, 'abs') !== false){
			//Если меньше 50% — 0, в противном случае — максимальное значение
			$hsb[$key] = ($hsb[$key] < ($hsbMax[$key] / 2)) ? 0 : $hsbMax[$key];
		}
		
		//Если нужно инвертировать
		if(strpos($sim, 'r') !== false){
			$hsb[$key] = $hsbMax[$key] + (-1 * $hsb[$key]);
		}
		
		//Обрабатываем слишком маленькие значения
		if($hsb[$key] < 0) $hsb[$key] = $hsbMax[$key] + $hsb[$key];
	}
	
	//Обрабатываем слишком большие значения
	if($hsb['H'] > $hsbMax['H']) $hsb['H'] = $hsb['H'] - $hsbMax['H'];
	if($hsb['S'] > $hsbMax['S']) $hsb['S'] = $hsbMax['S'];
	if($hsb['B'] > $hsbMax['B']) $hsb['B'] = $hsbMax['B'];
	
	//Результат
	$result = null;
	
	switch($outputFormat){
		case 'hsb':
			$result = $hsb['H'] . ',' .$hsb['S'] . ',' .$hsb['B'];
			break;
		case 'hex':
			$result = implode('', ddHSBtoHEX($hsb));
			break;
	}
	return $result;
}
?>