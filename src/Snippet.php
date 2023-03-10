<?php
namespace ddColorTools;

class Snippet extends \DDTools\Snippet {
	protected
		$version = '3.0.0',
		
		$params = [
			//Defaults
			'inputColor' => '',
			'inputColor_docField' => null,
			'inputColor_docId' => null,
			'offset_h' => '+0',
			'offset_s' => '+0',
			'offset_l' => '+0',
			'result_outputFormat' => 'hsl',
			'result_tpl' => '',
			'result_tpl_placeholders' => [],
		],
		
		$paramsTypes = [
			'result_tpl_placeholders' => 'objectArray'
		]
	;
		
	/**
	 * prepareParams
	 * @version 1.0 (2023-03-10)
	 * 
	 * @param $this->params {stdClass|arrayAssociative|stringJsonObject|stringHjsonObject|stringQueryFormatted}
	 * 
	 * @return {void}
	 */
	protected function prepareParams($paramsRenameMe = []){
		//Call base method
		parent::prepareParams($paramsRenameMe);
		
		//Если задано имя поля, которое необходимо получить
		if(!empty($this->params->inputColor_docField)){
			$this->params->inputColor = \DDTools\Snippet::runSnippet([
				'name' => 'ddGetDocumentField',
				'params' => [
					'dataProviderParams' => [
						'resourceId' => $this->params->inputColor_docId,
						'resourceFields' => $this->params->inputColor_docField
					]
				]
			]);
		}
		
		//Case-insensitive
		foreach (
			[
				'inputColor',
				'result_outputFormat',
			] as
			$paramName
		){
			$this->params->{$paramName} = strtolower($this->params->{$paramName});
		}
		
		//Comma separated strings
		foreach (
			[
				'offset_h',
				'offset_s',
				'offset_l',
			] as
			$paramName
		){
			$this->params->{$paramName} = explode(
				',',
				$this->params->{$paramName}
			);
		}
		
		$this->params->result_tpl = \ddTools::$modx->getTpl($this->params->result_tpl);
	}
	
	/**
	 * run
	 * @version 1.0.9 (2023-03-10)
	 * 
	 * @return {string}
	 */
	public function run(){
		//The snippet must return an empty string even if result is absent
		$result = '';
		
		//Required parameter
		if(!empty($this->params->inputColor)){
			$hslRange = (object) [
				'h' => $this->params->offset_h,
				's' => $this->params->offset_s,
				'l' => $this->params->offset_l
			];
			
			$hslMax = (object) [
				'h' => 360,
				's' => 100,
				'l' => 100
			];
			
			//If input color set as HSL
			if (
				strpos(
					$this->params->inputColor,
					'hsl'
				) !== false
			){
				//Remove unwanted chars
				$this->params->inputColor = str_replace(
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
					$this->params->inputColor
				);
				
				$this->params->inputColor = explode(
					',',
					$this->params->inputColor
				);
				
				$resultColorHsl = (object) [
					'h' => $this->params->inputColor[0],
					's' => $this->params->inputColor[1],
					'l' => $this->params->inputColor[2]
				];
			//AS RGB
			}else{
				//Удалим из цвета символ '#'
				$this->params->inputColor = str_replace(
					'#',
					'',
					$this->params->inputColor
				);
				
				//Преобразуем цвет в HSL
				$resultColorHsl = static::hexToHsl($this->params->inputColor);
			}
			
			foreach(
				$resultColorHsl as
				$key =>
				$val
			){
				foreach (
					$hslRange->{$key} as
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
					if(
						strpos(
							$operation_sign,
							'+'
						) !==
						false
					){
						$resultColorHsl->{$key} += $operation;
					//Если нужно отнять
					}elseif(
						strpos(
							$operation_sign,
							'-'
						) !==
						false
					){
						$resultColorHsl->{$key} -= $operation;
					//Если нужно приравнять (если есть хоть какое-то число)
					}elseif(strlen($operation) > 0){
						$resultColorHsl->{$key} = $operation;
					}
					
					//Если нужно задать максимальное, либо минимальное значение
					if(
						strpos(
							$operation_sign,
							'abs'
						) !==
						false
					){
						//Если меньше 50% — 0, в противном случае — максимальное значение
						$resultColorHsl->{$key} =
							(
								$resultColorHsl->{$key} <
								($hslMax->{$key} / 2)
							) ?
							0 :
							$hslMax->{$key}
						;
					}
					
					//Если нужно инвертировать
					if(
						strpos(
							$operation_sign,
							'r'
						) !==
						false
					){
						$resultColorHsl->{$key} =
							$hslMax->{$key} +
							(-1 * $resultColorHsl->{$key})
						;
					}
					
					//Обрабатываем слишком маленькие значения
					if($resultColorHsl->{$key} < 0){
						$resultColorHsl->{$key} =
							$hslMax->{$key} +
							$resultColorHsl->{$key}
						;
					}
				}
			}
			
			//Обрабатываем слишком большие значения
			if($resultColorHsl->h > $hslMax->h){
				$resultColorHsl->h = $resultColorHsl->h - $hslMax->h;
			}
			if($resultColorHsl->s > $hslMax->s){
				$resultColorHsl->s = $hslMax->s;
			}
			if($resultColorHsl->l > $hslMax->l){
				$resultColorHsl->l = $hslMax->l;
			}
			
			switch($this->params->result_outputFormat){
				case 'hsl':
					$result =
						'hsl(' .
						$resultColorHsl->h .
						',' .
						$resultColorHsl->s .
						'%,' .
						$resultColorHsl->l .
						'%)'
					;
				break;
				
				case 'hex':
					$result = static::hsbToHex(
						static::hslToHsb($resultColorHsl)
					);
				break;
			}
			
			if (!empty($this->params->result_tpl)){
				$result = [
					'ddResult' => $result,
					'ddH' => $resultColorHsl->h,
					'ddS' => $resultColorHsl->s,
					'ddL' => $resultColorHsl->l
				];
				
				//Если есть дополнительные данные
				if (!empty($this->params->result_tpl_placeholders)){
					$result = \DDTools\ObjectTools::extend([
						'objects' => [
							$result,
							$this->params->result_tpl_placeholders
						]
					]);
				}
				
				$result = \ddTools::parseText([
					'text' => $this->params->result_tpl,
					'data' => $result
				]);
			}
		}
		
		return $result;
	}
	
	/**
	 * hexToHsl
	 * @version 3.0.1 (2023-03-10)
	 * 
	 * @param $hex {string} — Color in HEX format without first '#'. @required
	 * 
	 * @return $result {stdClass}
	 * @return $result->h {integer}
	 * @return $result->s {integer}
	 * @return $result->l {integer}
	 */
	private static function hexToHsl($hex): \stdClass {
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
		
		$resultHsl = new \stdClass();
		
		//Вычисляем яркость (от 0 до 100)
		$resultHsl->l = round(
			($max + $min) / 2 * 100 / 255
		);
		
		//Если цвет серый
		if($max == $min){
			$resultHsl->s = 0;
			$resultHsl->h = 0;
		}else{
			//Вычисляем насыщенность
			$resultHsl->s = round(
				(
					$resultHsl->l > 50 ?
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
			
			$resultHsl->h =
				(
					round($hue * 60) +
					360
				) %
				360
			;
		}
		
		return $resultHsl;
	}
	
	/**
	 * hsbToRgb
	 * @version 2.0.1 (2023-03-10)
	 * 
	 * @param $paramHsb {stdClass|arrayAssociative} — Color in HSB format. @required
	 * @param $paramHsb->h {integer} — Hue. @required
	 * @param $paramHsb->s {integer} — Saturation. @required
	 * @param $paramHsb->b {integer} — Brightness. @required
	 * 
	 * @return $result {stdClass}
	 * @return $result->r {integer}
	 * @return $result->g {integer}
	 * @return $result->b {integer}
	 */
	private static function hsbToRgb($paramHsb): \stdClass {
		$paramHsb = (object) $paramHsb;
		
		$saturation = $paramHsb->s;
		$brightness = $paramHsb->b;
		
		$resultRgb = new \stdClass();
		
		//Если цвет серый
		if($saturation == 0){
			$resultRgb->r = $brightness;
			$resultRgb->g = $brightness;
			$resultRgb->b = $brightness;
		}else{
			$hue = ($paramHsb->h + 360) % 360;
			$hue2 = floor($hue / 60);
			
			$dif =
				($hue % 60) /
				60
			;
			$mid1 =
				$brightness *
				(
					100 -
					$saturation * $dif
				) /
				100
			;
			$mid2 =
				$brightness *
				(
					100 -
					$saturation * (1 - $dif)
				) /
				100
			;
			$min =
				$brightness *
				(100 - $saturation) /
				100
			;
			
			if($hue2 == 0){
				$resultRgb->r = $brightness;
				$resultRgb->g = $mid2;
				$resultRgb->b = $min;
			}elseif($hue2 == 1){
				$resultRgb->r = $mid1;
				$resultRgb->g = $brightness;
				$resultRgb->b = $min;
			}elseif($hue2 == 2){
				$resultRgb->r = $min;
				$resultRgb->g = $brightness;
				$resultRgb->b = $mid2;
			}elseif($hue2 == 3){
				$resultRgb->r = $min;
				$resultRgb->g = $mid1;
				$resultRgb->b = $brightness;
			}elseif($hue2 == 4){
				$resultRgb->r = $mid2;
				$resultRgb->g = $min;
				$resultRgb->b = $brightness;
			}else{
				$resultRgb->r = $brightness;
				$resultRgb->g = $min;
				$resultRgb->b = $mid1;
			}
		}
		
		//Переводим из системы счисления от 0 до 100 в от 0 до 255
		foreach (
			$resultRgb as
			$key =>
			$val
		){
			$resultRgb->{$key} = intval(
				round($val / 100 * 255)
			);
		}
		
		return $resultRgb;
	}
	
	/**
	 * hsbToHex
	 * @version 5.0.1 (2023-03-10)
	 * 
	 * @param $paramHsb {stdClass|arrayAssociative} — Color in HSB format. @required
	 * @param $paramHsb->h {integer} — Hue. @required
	 * @param $paramHsb->s {integer} — Saturation. @required
	 * @param $paramHsb->b {integer} — Brightness. @required
	 * 
	 * @return $result {string}
	 */
	private static function hsbToHex($paramHsb): string {
		$resultRgb = static::hsbToRgb($paramHsb);
		
		//Обходим массив и преобразовываем все значения в hex
		foreach (
			$resultRgb as
			$key =>
			$val
		){
			$resultRgb->{$key} = dechex($val);
			
			//Если не хватает ноля, дописываем
			if (strlen($resultRgb->{$key}) < 2){
				$resultRgb->{$key} = '0' . $resultRgb->{$key};
			}
		}
		
		return implode(
			'',
			(array) $resultRgb
		);
	}
	
	/**
	 * hsbToHsl
	 * @version 1.0 (2023-03-10)
	 * 
	 * @param $paramHsb {stdClass|arrayAssociative} — Color in HSB format. @required
	 * @param $paramHsb->h {integer} — Hue. @required
	 * @param $paramHsb->s {integer} — Saturation. @required
	 * @param $paramHsb->b {integer} — Brightness. @required
	 * 
	 * @return $result {stdClass}
	 * @return $result->h {integer}
	 * @return $result->s {integer}
	 * @return $result->l {integer}
	 */
	private static function hsbToHsl($paramHsb): \stdClass {
		$paramHsb = (object) $paramHsb;
		
		$resultHsl = (object) [
			'h' => $paramHsb->h,
			's' => $paramHsb->s,
			//Determine the lightness in the range [0, 100]
			'l' => intval(
				(2 - $paramHsb->s / 100) *
				$paramHsb->b /
				2
			)
		];
		
		if ($resultHsl->l != 0){
			if ($resultHsl->l == 100){
				$resultHsl->s = 0;
			}else{
				$resultHsl->s = intval(
					$resultHsl->s * $paramHsb->b /
					(
						$resultHsl->l < 50 ?
						$resultHsl->l * 2 :
						200 - $resultHsl->l * 2
					)
				);
			}
		}
		
		return $resultHsl;
	}
	
	/**
	 * hslToHsb
	 * @version 1.0 (2023-03-10)
	 * 
	 * @param $paramHsl {stdClass|arrayAssociative} — Color in HSL format. @required
	 * @param $paramHsl->h {integer} — Hue. @required
	 * @param $paramHsl->s {integer} — Saturation. @required
	 * @param $paramHsl->l {integer} — Lightness. @required
	 * 
	 * @return $result {stdClass}
	 * @return $result->h {integer}
	 * @return $result->s {integer}
	 * @return $result->b {integer}
	 */
	private static function hslToHsb($paramHsl): \stdClass {
		$paramHsl = (object) $paramHsl;
		
		$resultHsb = (object) [
			'h' => $paramHsl->h,
			's' => 0,
			'b' => 0
		];
		
		if ($paramHsl->l != 0){
			$temp =
				$paramHsl->s *
				(
					$paramHsl->l < 50 ?
					$paramHsl->l :
					100 - $paramHsl->l
				) /
				100
			;
			
			$resultHsb->b = intval(round(
				$paramHsl->l + $temp
			));
			
			$resultHsb->s = intval(round(
				200 * $temp / $resultHsb->b
			));
		}
		
		return $resultHsb;
	}
}