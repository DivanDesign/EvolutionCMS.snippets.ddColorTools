<?php
/**
 * ddColorTools
 * @version 3.1 (2023-03-10)
 * 
 * @see README.md
 * 
 * @link https://code.divandesign.biz/modx/ddcolortools
 * 
 * @copyright 2011–2023 DD Group {@link https://DivanDesign.biz }
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