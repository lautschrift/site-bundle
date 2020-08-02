<?php
/**
 * Back end modules
*/

if(TL_MODE == "BE") {
    $GLOBALS['TL_CSS'][] = '/bundles/site/palafittes.css';
}

array_insert($GLOBALS['BE_MOD'], array_search('content', array_keys($GLOBALS['BE_MOD'])) + 1, array('Site' => array()));

array_insert($GLOBALS['BE_MOD']['Site'],1, array
(
        'palafittes_site' => array
        (
            'tables' => array ('tl_site', 'tl_site_details', 'tl_content'),
        ),
));

$GLOBALS['TL_CTE']['Site'] = array(
	'Site' => 'Lautschrift\\SiteBundle\\Resources\\contao\\classes\\ContentSite'
);

//$GLOBALS['TL_CTE']['Museum']['Museum'] = '\\lautschrift\\museum-bundle\\src\\Resources\\contao\\classes\\ContentMuseum';
