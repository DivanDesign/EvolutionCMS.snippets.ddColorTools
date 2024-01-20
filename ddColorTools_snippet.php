<?php
/**
 * ddColorTools
 * @version 3.2.1 (2024-01-20)
 * 
 * @see README.md
 * 
 * @link https://code.divandesign.ru/modx/ddcolortools
 * 
 * @copyright 2011–2024 Ronef {@link https://Ronef.ru }
 */

//Include (MODX)EvolutionCMS.libraries.ddTools
require_once(
	$modx->getConfig('base_path') .
	'assets/libs/ddTools/modx.ddtools.class.php'
);

return \DDTools\Snippet::runSnippet([
	'name' => 'ddColorTools',
	'params' => $params
]);
?>