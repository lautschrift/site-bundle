
<?php
$GLOBALS['TL_LANG']['site_details_type']['wert0'] = '- Typ auswählen –';
$GLOBALS['TL_LANG']['site_details_type']['wert1'] = 'Fundstellen Details';
$GLOBALS['TL_LANG']['site_details_type']['wert2'] = 'Audio Fundstelle';

$GLOBALS['TL_LANG']['site_details_type']['DE'] = 'Deutsch';
$GLOBALS['TL_LANG']['site_details_type']['EN'] = 'Englisch';
$GLOBALS['TL_LANG']['site_details_type']['FR'] = 'Französisch';
$GLOBALS['TL_LANG']['site_details_type']['SLO'] = 'Slowenisch';

// contao/dca/tl_parts.php
$GLOBALS['TL_DCA']['tl_site_details'] = [
    'config' => [
        'dataContainer' => 'Table',
        'enableVersioning' => true,
        'ptable' => 'tl_site',
        'sql' => [
            'keys' => [
                'id' => 'primary'
            ],
        ],
        'onload_callback' => [
            function (\Contao\DataContainer $dc) {
                $db = \Contao\Database::getInstance();
                $pid = \Contao\Input::get('pid');
                $result = $db->prepare('SELECT `unescoid` FROM `tl_site` WHERE `id` = ?')
                             ->execute([$pid]);
                $prefix = $result->name; //strtoupper(substr($result->name, 0, 2));
                $GLOBALS['TL_DCA']['tl_site_details']['fields']['number']['default'] = $prefix;
                //$GLOBALS['TL_DCA']['tl_site_details']['fields']['name']['default'] = $prefix;//$result->name;
            },
        ]
    ],

    'list' => [
        'sorting' => [
            'mode' => 4,
            'fields' => ['name'],
            'headerFields' => ['unescoid', 'name'],
            'panelLayout' => 'search,limit',
            'child_record_callback' => function (array $row) {
                return '<div class="tl_content_left">'.$row['name'].' ['.$row['number'].']</div>';
            },
        ],
        'operations' => [
            'edit' => [
                'href' => 'act=edit',
                'icon' => 'edit.svg',
            ],
            'copy' => [
                'href' => 'act=paste&amp;mode=copy',
                'icon' => 'copy.svg'
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

    'fields' => [
            'id' => [
                'sql' => ['type' => 'integer', 'unsigned' => true, 'autoincrement' => true],
            ],
            'pid' => [
                'foreignKey' => 'tl_site.name',
                'sql' => ['type' => 'integer', 'unsigned' => true, 'default' => 0],
                'relation' => ['type'=>'belongsTo', 'load'=>'lazy']
            ],
            'tstamp' => [
                'sql' => ['type' => 'integer', 'unsigned' => true, 'default' => 0]
            ],
            'type' => [
                'label' => &$GLOBALS['TL_LANG']['tl_site']['type'],
                'inputType' => 'select',
                'eval' 		=> array('submitOnChange' => true, 'tl_class' => 'clr', 'mandatory' => true),
        		'options' 	=> ['wert0','wert1', 'wert2'],
        		'reference' => &$GLOBALS['TL_LANG']['site_details_type'],
                'sql' => ['type' => 'string', 'length' => 20, 'default' => 0]
            ],
            'speech' => [
                'label' => &$GLOBALS['TL_LANG']['tl_site']['speech'],
                'inputType' => 'select',
                'eval' 		=> array('submitOnChange' => true, 'tl_class' => 'clr', 'mandatory' => true),
        		'options' 	=> ['DE', 'EN', 'FR', 'SLO'],
        		'reference' => &$GLOBALS['TL_LANG']['site_details_speech'],
                'sql' => ['type' => 'string', 'length' => 3, 'default' => 0]
            ],
            'datation' => [
                'label' => &$GLOBALS['TL_LANG']['tl_site_details']['datation'],
                'search' => true,
                'flag' => 1,
                'inputType' => 'text',
                'eval' => ['tl_class' => 'w50', 'maxlength' => 255, 'mandatory' => false],
                'sql' => ['type' => 'string', 'length' => 255, 'default' => '']
            ],
            'genericdatation' => [
                'label' => &$GLOBALS['TL_LANG']['tl_site_details']['genericdatation'],
                'search' => true,
                'flag' => 1,
                'inputType' => 'text',
                'eval' => ['tl_class' => 'w50', 'maxlength' => 255, 'mandatory' => false],
                'sql' => ['type' => 'string', 'length' => 255, 'default' => '']
            ],
            'activities' => [
                'label' => &$GLOBALS['TL_LANG']['tl_site_details']['activities'],
                'search' => true,
                'inputType' => 'textarea',
                'eval' => ['tl_class' => 'clr', 'rte' => 'tinyMCE', 'mandatory' => false],
                'sql' => ['type' => 'text', 'notnull' => false]
            ],
            'features' => [
                'label' => &$GLOBALS['TL_LANG']['tl_site_details']['features'],
                'search' => true,
                'inputType' => 'textarea',
                'eval' => ['tl_class' => 'clr', 'rte' => 'tinyMCE', 'mandatory' => false],
                'sql' => ['type' => 'text', 'notnull' => false]
            ],
            'museumlink' => [
                'label' => &$GLOBALS['TL_LANG']['tl_site_details']['museumlink'],
                'search' => true,
                'inputType' => 'text',
                'eval' => ['tl_class' => 'clr', 'rte' => 'tinyMCE', 'mandatory' => false],
                'sql' => ['type' => 'text', 'notnull' => false]
            ],
            'name' => [
                'label' => &$GLOBALS['TL_LANG']['tl_site_details']['name'],
                'search' => true,
                'flag' => 1,
                'inputType' => 'text',
                'eval' => ['tl_class' => 'w50', 'maxlength' => 255, 'mandatory' => true],
                'sql' => ['type' => 'string', 'length' => 255, 'default' => '']
            ],
            'number' => [
                'label' => &$GLOBALS['TL_LANG']['tl_site_details']['number'],
                'search' => true,
                'inputType' => 'text',
                'eval' => ['tl_class' => 'w50', 'maxlength' => 255, 'mandatory' => true],
                'sql' => ['type' => 'string', 'length' => 255, 'default' => '']
            ],
            'description' => [
                'label' => &$GLOBALS['TL_LANG']['tl_site_details']['description'],
                'inputType' => 'textarea',
                'eval' => ['tl_class' => 'clr', 'rte' => 'tinyMCE', 'mandatory' => true],
                'sql' => ['type' => 'text', 'notnull' => false]
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
        'palettes' => [
           '__selector__' => ['type'],
           'default' => '{type_legend},type',
           'wert1' =>   '{type_legend},type;'.
                        '{details_legend},speech,genericdatation,datation,description;'.
                        '{optionaldetails_legend:hide},activities, features, museumlink;',
           'wert2' =>   '{type_legend},type;{image_legend},singleSRC, name',
       ],
];
