<?php
/**
 * Back end modules
*/

/*
array_insert($GLOBALS['BE_MOD']['content'], 1, array
(
	'Museum' => array
	(
		'tables'	=> array('tl_museum'),

	)
));
*/
/**
 * Content elements
*/
/*
array_insert($GLOBALS['TL_CTE']['Museum'] ,1, array(
		'museum' => 'Lautschrift\\Museum-Bundle\\ContentMuseum'
));

*/

/*

$GLOBALS['TL_CTE']['Museum'] = array
(
	'museum' => 'MuseumBundle\Resources\ContentMuseum',

);


array_insert($GLOBALS['TL_CTE']['Museum'] ,1, array(
		'museum' => 'Lautschrift\\MuseumBundle\\Resources\\contao\\classes\\ContentMuseum'
));
*/

$GLOBALS['TL_CTE']['Site'] = array(
	'Museum' => 'Lautschrift\\SiteBundle\\Resources\\contao\\classes\\ContentSite'
);

//$GLOBALS['TL_CTE']['Museum']['Museum'] = '\\lautschrift\\museum-bundle\\src\\Resources\\contao\\classes\\ContentMuseum';
$GLOBALS['TL_LANG']['CTE']['Site'] = array('Site','Einbinden einer Fundstelle.');
