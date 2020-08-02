<?php

use \con4gis\MapsBundle\Classes\GeoPicker;

/**
 * Table tl_site
 */



$GLOBALS['TL_LANG']['site_country']['wert1'] = 'Deutschland';
$GLOBALS['TL_LANG']['site_country']['wert2'] = 'Österreich';
$GLOBALS['TL_LANG']['site_country']['wert3'] = 'Schweiz';
$GLOBALS['TL_LANG']['site_country']['wert4'] = 'Italien';
$GLOBALS['TL_LANG']['site_country']['wert5'] = 'Slowenien';
$GLOBALS['TL_LANG']['site_country']['wert6'] = 'Frankreich';



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
    		'options' 	=> ['wert1', 'wert2', 'wert3','wert4','wert5','wert6'],
    		'reference' => &$GLOBALS['TL_LANG']['site_country'],
            'sql' => ['type' => 'string', 'length' => 20, 'default' => '']
        ],
        'geoX' => [
            'label' => &$GLOBALS['TL_LANG']['tl_site']['geoX'],
            'eval'                    => array('mandatory'=>false, 'maxlength'=>20, 'tl_class'=>'w50 wizard' ),
            'inputType'               => 'c4g_text',
            'save_callback'           => [['tl_site_c4g_maps_site', 'setLocLon']],
            'wizard'                  => [['\con4gis\MapsBundle\Classes\GeoPicker', 'getPickerLink']],
            'sql' => ['type' => 'string', 'length' => 20, 'default' => '']
        ],
        'geoY' => [
            'label' => &$GLOBALS['TL_LANG']['tl_site']['geoY'],
            'eval'                    => array('mandatory'=>false, 'maxlength'=>20, 'tl_class'=>'w50 wizard' ),
            'inputType'               => 'c4g_text',
            'save_callback'           => [['tl_site_c4g_maps_site', 'setLocLat']],
            'wizard'                  => [['\con4gis\MapsBundle\Classes\GeoPicker', 'getPickerLink']],
            'sql' => ['type' => 'string', 'length' => 20, 'default' => '']
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


/**
 * Class t tl_site_c4g_maps_site
 */

use \con4gis\MapsBundle\Classes\Utils;

class tl_site_c4g_maps_site extends Backend
{

    protected $firstMapId = null;

    /**
     * Return all base layers for current Map Profile as array
     * @param object
     * @return array
     */
    public function get_baselayers(DataContainer $dc)
    {
        $id = 0;
        if ($dc->activeRecord->c4g_map_id != 0) {
            $id = $dc->activeRecord->c4g_map_id;
        } else {
            // take firstMapId, because it will be chosen as DEFAULT value for c4g_map_id
            $id = $this->firstMapId;
        }

        $profile = $this->Database->prepare(
                "SELECT b.baselayers ".
                "FROM tl_c4g_maps a, tl_c4g_map_profiles b ".
                "WHERE a.id = ? and a.profile = b.id")
                ->execute($id);

        $ids = deserialize($profile->baselayers,true);
        if (count($ids)>0) {
            $baseLayers = $this->Database->prepare("SELECT id,name FROM tl_c4g_map_baselayers WHERE id IN (".implode(',',$ids).") ORDER BY name")->execute();
        } else {
            $baseLayers = $this->Database->prepare("SELECT id,name FROM tl_c4g_map_baselayers ORDER BY name")->execute();
        }


        if ($baseLayers->numRows > 0) {
            while ( $baseLayers->next () ) {
                $return [$baseLayers->id] = $baseLayers->name;
            }
        }
        return $return;
    }

    /**
     * Return all defined maps
     * @param object
     * @return array
     */
    public function get_maps(DataContainer $dc)
    {
        $maps = $this->Database->prepare ( "SELECT * FROM tl_c4g_maps WHERE is_map=1 AND published=1" )->execute ();
        if ($maps->numRows > 0) {
            while ( $maps->next () ) {
                if (!isset($this->firstMapId)) {
                    // save first map id
                    $this->firstMapId = $maps->id;
                }
                $return [$maps->id] = $maps->name;
            }
        }
        return $return;
    }
    public function getLocStyles(\DataContainer $dc)
    {
        $locStyles = $this->Database->prepare("SELECT id,name FROM tl_c4g_map_locstyles ORDER BY name")
            ->execute();
        $return[''] = '-';
        while ($locStyles->next())
        {
            $return[$locStyles->id] = $locStyles->name;
        }
        return $return;
    }

    /**
     * Validate Longitude
     */
    public function setLocLon($varValue, \DataContainer $dc)
    {
        if ($varValue != 0)
        {
            if (!\con4gis\MapsBundle\Resources\contao\classes\Utils::validateLon($varValue))
            {
                throw new \Exception($GLOBALS['TL_LANG']['c4g_maps']['geox_invalid']);
            }
        }
        return $varValue;
    }

    /**
     * Validate Latitude
     */
    public function setLocLat($varValue, \DataContainer $dc)
    {
        if ($varValue != 0)
        {
            if (!\con4gis\MapsBundle\Resources\contao\classes\Utils::validateLat($varValue))
            {
                throw new \Exception($GLOBALS['TL_LANG']['c4g_maps']['geoy_invalid']);
            }
        }
        return $varValue;
    }
}
