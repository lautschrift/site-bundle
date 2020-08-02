<?php

use \con4gis\MapsBundle\Classes\GeoPicker;

/**
 * Table tl_site
 */

 $GLOBALS['TL_DCA']['tl_site'] = [


     // Config
     'config' => [

         'dataContainer'               => 'Table',
         'ctable'                      => ['tl_site_details'],
         'enableVersioning'            => true,
                 'sql' => array
         (
             'keys' => array
             (
                 'id' => 'primary'
             )
         )
     ],
     // List
     'list' => [
        'sorting' => [
            'mode' => 1,
            'fields' => ['unescoid','name'],
            'flag' => 1,
            'panelLayout' => 'search,limit'
        ],
        'label' => [
            'fields' => ['name'],
            'format' => '%s',
        ],
        'operations' => [
            'edit' => [
                'href' => 'table=tl_site_details',
                'icon' => 'edit.svg',
            ],
            'editheader' => [
                'href' => 'act=edit',
                'icon' => 'header.svg',
            ],
            'delete' => [
                'href' => 'act=delete',
                'icon' => 'delete.svg',
            ],
            'show' => [
                'href' => 'act=show',
                'icon' => 'show.svg'
            ],
        ],
    ],
     // Fields
     'fields' => [
        'id' => [
            'sql' => ['type' => 'integer', 'unsigned' => true, 'autoincrement' => true]
        ],
        'tstamp' => [
            'sql' => ['type' => 'integer', 'unsigned' => true, 'default' => 0]
        ],
        'unescoid' => [
            'label' => &$GLOBALS['TL_LANG']['tl_site']['unescoid'],
            'search' => true,
            'inputType' => 'text',
            'eval' => ['tl_class' => 'w50', 'maxlength' => 255, 'mandatory' => true],
            'sql' => ['type' => 'string', 'length' => 255, 'default' => '']
        ],
        'name' => [
            'label' => &$GLOBALS['TL_LANG']['tl_site']['name'],
            'search' => true,
            'inputType' => 'text',
            'eval' => ['tl_class' => 'w50', 'maxlength' => 255, 'mandatory' => true],
            'sql' => ['type' => 'string', 'length' => 255, 'default' => '']
        ],
        'region' => [
           'label' => &$GLOBALS['TL_LANG']['tl_site']['region'],
           'search' => true,
           'inputType' => 'text',
           'eval' => ['tl_class' => 'w50', 'maxlength' => 255, 'mandatory' => true],
           'sql' => ['type' => 'string', 'length' => 255, 'default' => '']
       ],
        'country' => [
            'label' => &$GLOBALS['TL_LANG']['tl_site']['country'],
            'inputType' => 'select',
            'eval' 		=> array('submitOnChange' => true, 'tl_class' => 'w50'),
    		'options' 	=> ['wert1', 'wert2','wert3','wert4','wert5','wert6'],
    		'reference' => &$GLOBALS['TL_LANG']['site_country'],
            'sql' => ['type' => 'string', 'length' => 2, 'default' => '']
        ],
        'geoX' => [
            'label' => &$GLOBALS['TL_LANG']['tl_site']['geoX'],
            'eval'                    => array('mandatory'=>false, 'maxlength'=>20, 'tl_class'=>'w50 wizard' ),
            'inputType'               => 'c4g_text',
            'save_callback'           => [['tl_content_c4g_maps', 'setLocLon']],
            'wizard'                  => [['\con4gis\MapsBundle\Classes\GeoPicker', 'getPickerLink']],
            'sql'                     => "varchar(20) NOT NULL default ''"
        ],
        'geoY' => [
            'label' => &$GLOBALS['TL_LANG']['tl_site']['geoY'],
            'eval'                    => array('mandatory'=>false, 'maxlength'=>20, 'tl_class'=>'w50 wizard' ),
            'inputType'               => 'c4g_text',
            'save_callback'           => [['tl_content_c4g_maps', 'setLocLat']],
            'wizard'                  => [['\con4gis\MapsBundle\Classes\GeoPicker', 'getPickerLink']],
            'sql'                     => "varchar(20) NOT NULL default ''"
        ],
        'singleSRC' => [
            'label' => &$GLOBALS['TL_LANG']['tl_site_details']['singleSRC'],
            'inputType' => 'fileTree',
            'eval' => [
                'tl_class' => 'clr',
                'mandatory' => true,
                'fieldType' => 'radio',
                'filesOnly' => true,
                'extensions' => \Contao\Config::get('validImageTypes'),
                'mandatory' => true,
            ],
            'sql' => ['type' => 'binary', 'length' => 16, 'notnull' => false, 'fixed' => true]
        ],
    ],

     // Palettes
     'palettes' => [
         'default' => '{site_legend},unescoid, name;{place_legend},region, country, geoX, geoY;{pic_legend},singleSRC'
    ]
];


/*


$GLOBALS['TL_DCA']['tl_site']['config']['tl_Site'] = array
(
	'dataContainer'               => 'Table',
	'switchToEdit'                => true,
	'enableVersioning'            => true,
	'sql' => array
	(
		'keys' => array
		(
			'id' => 'primary',
			'alias' => 'index',
		)
	)
);

$GLOBALS['TL_DCA']['tl_site']['palettes']['Site'] = '
	{type_legend},type,headline;
	{museum_legend},title,museum_name, museum_street, museum_nr, museum_plz, museum_ort, museum_land, museum_email, museum_website, museum_lang, museum_pic, museum_geox, museum_geoy;
	{museumtext_legend},text, museum_openings;
	{image_legend},addImage;
	{template_legend:hide},customTpl;
	{protected_legend:hide},protected;
	{expert_legend:hide},guests,cssID;
	{invisible_legend:hide},invisible,start,stop
';


$GLOBALS['TL_DCA']['tl_site']['fields']['museum_name'] = array(
		'label' 	=> array('Name', 'Name der Fundstelle'),
		'eval' 		=> array('tl_class' ),
		'inputType' => 'text',
		'sql'      	=> "varchar(255) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_site']['fields']['museum_street'] = array(
		'label' 	=> array('Straße', 'Straße'),
        'eval' 		=> array('tl_class' => 'w50'),
        'inputType' => 'text',
		'sql'       => "varchar(255) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_site']['fields']['museum_nr'] = array(
		'label' 	=> array('Hausnumer', 'Hausnummer'),
        'eval' 		=> array('tl_class' => 'w50'),
        'inputType' => 'text',
		'sql'       => "varchar(255) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_site']['fields']['museum_plz'] = array(
		'label' 	=> array('Postleitzahl', 'Postleitzahl'),
        'eval' 		=> array('tl_class' => 'w50'),
        'inputType'	=> 'text',
		'sql'       => "varchar(255) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_site']['fields']['museum_ort'] = array(
		'label' 	=> array('Ort', 'Ort'),
		'eval' 		=> array('tl_class' => 'w50'),
		'inputType' => 'text',
		'sql'       => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_LANG']['tl_museum_country']['wert1'] = 'Deutschland';
$GLOBALS['TL_LANG']['tl_museum_country']['wert2'] = 'Österreich';
$GLOBALS['TL_LANG']['tl_museum_country']['wert3'] = 'Schweiz';
$GLOBALS['TL_LANG']['tl_museum_country']['wert4'] = 'Italien';
$GLOBALS['TL_LANG']['tl_museum_country']['wert5'] = 'Slowenien';
$GLOBALS['TL_LANG']['tl_museum_country']['wert6'] = 'Frankreich';

$GLOBALS['TL_DCA']['tl_site']['fields']['museum_land'] = array(
		'label' 	=> array('Land', ''),
        'eval' 		=> array('submitOnChange' => true, 'tl_class' => 'clr'),
		'options' 	=> array('wert1','wert2','wert3','wert4','wert5','wert6'),
		'reference' => &$GLOBALS['TL_LANG']['tl_museum_country'],
        'inputType' => 'select',
		'sql'       => "varchar(255) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_site']['fields']['museum_email'] = array(
		'label' 	=> array('E-Mail', 'E-Mail Adresse'),
		'eval' 		=> array('tl_class' => 'w50 wizard'),
		'wizard' 	=> array(array('tl_site', 'pagePicker')),
		'inputType' => 'text',
		'sql'       => "varchar(255) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_site']['fields']['museum_website'] = array(
		'label' 	=> array('Website', 'Website Adresse'),
		'eval' 		=> array('tl_class' => 'w50 wizard'),
		'wizard' 	=> array(array('tl_site', 'pagePicker')),
		'inputType' => 'text',
		'sql'       => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_site']['fields']['museum_geox'] = array(
		'label' 				  => array('Karte geoX', ''),
		'inputType'               => 'c4g_text',
		'eval'                    => array('mandatory'=>false, 'maxlength'=>20, 'tl_class'=>'w50 wizard' ),
        'save_callback'           => [['tl_content_c4g_maps', 'setLocLon']],
        'wizard'                  => [['\con4gis\MapsBundle\Classes\GeoPicker', 'getPickerLink']],
		'sql'                     => "varchar(20) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_site']['fields']['museum_geoy'] = array(
		'label' 				  => array('Karte geoY', ''),
        'eval'                    => array('mandatory'=>false, 'maxlength'=>20, 'tl_class'=>'w50 wizard' ),
        'inputType'               => 'c4g_text',
        'save_callback'           => [['tl_content_c4g_maps', 'setLocLat']],
        'wizard'                  => [['\con4gis\MapsBundle\Classes\GeoPicker', 'getPickerLink']],
		'sql'                     => "varchar(20) NOT NULL default ''"
);



$GLOBALS['TL_DCA']['tl_site']['fields']['museum_locstyle'] = array
(
		'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['c4g_locstyle'],
		'exclude'                 => true,
		'inputType'               => 'select',
		'eval' 					  => array('tl_class' => 'clr'),
		'options_callback'        => array('tl_calendar_events_c4g_maps','getLocStyles'),
		'sql'                     => "int(10) unsigned NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_site']['fields']['museum_lang'] = array(
		'label' 	=> array('Anzeigesprache', 'Anzeigesprache'),
		'eval' 		=> array('tl_class' => 'w50'),
		'inputType' => 'text',
		'sql'       => "varchar(255) NOT NULL default 'de'"
);

$GLOBALS['TL_DCA']['tl_site']['fields']['museum_openings'] = array(
		'label' 	=> array('Öffnungszeiten', 'Öffnungszeiten'),
		'eval' => array('rte' => 'tinyMCE'),
		'inputType' => 'textarea',
		'sql'       => "varchar(255) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_site']['fields']['museum_pic'] = array
(
		'label' 				=> array('Museum Bild', 'Bild das im Popup auf der Karte verwendet wird'),
		'exclude'               => true,
		'eval' 					=> array('tl_class' => 'w50 wizard'),
		'wizard' 				=> array(array('tl_site', 'pagePicker')),
		'inputType' 			=> 'text',
		'sql'       			=> "varchar(255) NOT NULL default ''"
);
*/
