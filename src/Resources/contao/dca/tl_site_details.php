
<?php
$GLOBALS['TL_LANG']['site_details_type']['wert0'] = '- Typ auswählen –';
$GLOBALS['TL_LANG']['site_details_type']['wert1'] = 'Fundstellen Details';
$GLOBALS['TL_LANG']['site_details_type']['wert2'] = 'Audio Fundstelle';

$GLOBALS['TL_LANG']['site_details_type']['DE'] = 'Deutsch';
$GLOBALS['TL_LANG']['site_details_type']['EN'] = 'Englisch';
$GLOBALS['TL_LANG']['site_details_type']['FR'] = 'Französisch';
$GLOBALS['TL_LANG']['site_details_type']['SLO'] = 'Slowenisch';
$GLOBALS['TL_LANG']['site_details_type']['IT'] = 'Italienisch';

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
                $prefix = $result->unescoid; //strtoupper(substr($result->name, 0, 2));
                $GLOBALS['TL_DCA']['tl_site_details']['fields']['number']['default'] = $prefix;
                $GLOBALS['TL_DCA']['tl_site_details']['fields']['speech']['default'] ='XXX';
            },
        ],
        'onsubmit_callback' => [
            function (\Contao\DataContainer $dc) {
                $db = \Contao\Database::getInstance();
                $id = \Contao\Input::get('id');

                $result = $db->prepare("SELECT pid, CONCAT_WS(';',pid,speech,published) AS detaillink FROM `tl_site_details` WHERE `id` = ?")
                            ->execute([$id]);
                $link = $result->detaillink;
                $link_parts = explode(";",$link);
                $pid = $link_parts[0];
                $locatedLink = $id.';'.$link_parts[1].';'.$link_parts[2];

                $getStoredIds = $db->prepare('SELECT `details_link` FROM `tl_site` WHERE `id` = ? ')
                                    -> execute([$pid]);

                if($getStoredIds->details_link != '') {
                    $allIds = json_decode($getStoredIds->details_link, true);
                    $all = explode(";",$allIds);
                }

                if(!in_array($locatedLink, $all[0]) || !in_array($locatedLink, $all[1])) {
                    $allIds[] = $locatedLink;
                    $allIdsAsString = json_encode($allIds);
                    $setChildToParent = $db->prepare('UPDATE `tl_site` SET `details_link` = ? WHERE `id` = ?')
                                            ->execute([$allIdsAsString, $pid]);
                }

            },
        ],
        'ondelete_callback' => [
            function (\Contao\DataContainer $dc) {
                $db = \Contao\Database::getInstance();
                $id = \Contao\Input::get('id');
                $result = $db->prepare("SELECT pid, CONCAT_WS(';',pid,speech,published) AS detaillink FROM `tl_site_details` WHERE `id` = ?")
                            ->execute([$id]);
                $link = $result->detaillink;
                $link_parts = explode(";",$link);
                $pid = $link_parts[0];
                $locatedLink = $id.';'.$link_parts[1].';'.$link_parts[2];

                $getStoredIds = $db->prepare('SELECT `details_link` FROM `tl_site` WHERE `id` = ?')
                                    -> execute([$pid]);
                if($getStoredIds->details_link != '') {
                    $allIds = json_decode($getStoredIds->details_link, true);

                    foreach ($allIds as $key=>$val) {
                       if ($val ==  $locatedLink || strpos($val ,"XXX")!==false) {
                           unset($allIds[$key]);
                       }
                   }
                    $actId = json_encode($allIds);
                }
                $removeChildId = $db->prepare('UPDATE `tl_site` SET `details_link` = ? WHERE `id` = ?')
                                        ->execute([$actId, $pid]);
            },
        ],
    ],

    'list' => [
        'sorting' => [
            'mode' => 4,
            'fields' => ['type'],
            'headerFields' => ['unescoid', 'name'],
            'panelLayout' => 'search,limit',
            'child_record_callback' => function (array $row) {
                return '<div class="tl_content_left"><b>'.$row['speech'].'</b> ['.$row['number'].']</div>';
            },
        ],
        'label' => [
            'fields' => ['speech'],
            'format' => '%s',
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
            'toggle' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_site_details']['toggle'],
                'icon'                => '/system/themes/flexible/icons/visible.svg',
                'attributes'          => 'onclick="Backend.getScrollOffset(); return AjaxRequest.toggleVisibility(this, %s);"',
                'button_callback'     => array('tl_site_details', 'toggleIcon')
            ),
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
            'published' => [
                'label'                   => &$GLOBALS['TL_LANG']['tl_site_details']['published'],
                'exclude'                 => true,
                'default'                 => true,
                'inputType'               => 'checkbox',
                'eval'                    => array('tl_class'=>'clr'),
                'sql'                     => "char(1) NOT NULL default '1'"
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

class tl_site_details extends Backend
{
    /**
     * Import BackendUser object
     */
    public function __construct()
    {
        parent::__construct();

        $this->import('BackendUser', 'User');
    }

    /**
     * Return the "toggle visibility" button
     */
    public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
    {
        if (strlen($this->Input->get('tid'))) {
            $this->toggleVisibility($this->Input->get('tid'), ($this->Input->get('state') == 1));
            $this->redirect($this->getReferer());
        }

        // Check permissions AFTER checking the tid, so hacking attempts are logged
        if (!$this->User->isAdmin && !$this->User->hasAccess('tl_site_details::published', 'alexf')) {
            return '';
        }

        $href .= '&amp;tid='.$row['id'].'&amp;state='.($row['published'] ? '' : 1);

        if (!$row['published']) {
            $icon = '/system/themes/flexible/icons/invisible.svg';
        }

        return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.\Image::getHtml($icon, $label).'</a> ';
    }


    /**
     * Disable/enable an element
     * @param integer
     * @param boolean
     */
    public function toggleVisibility($intId, $blnVisible)
    {
        // Check permissions to publish
        if (!$this->User->isAdmin && !$this->User->hasAccess('tl_site_details::published', 'alexf')) {

            //ToDo loggerService
            $this->log('Not enough permissions to publish/unpublish con4gis\MapsBundle\Resources\contao\classes\Utils ID "'.$intId.'"', 'tl_site_details toggleVisibility', TL_ERROR);
            $this->redirect('contao/main.php?act=error');
        }
        $this->createInitialVersion('tl_site_details', $intId);


        $objVersions = new Versions('tl_site_details', $intId);
        $objVersions->initialize();

        // Trigger the save_callback
        if (is_array($GLOBALS['TL_DCA']['tl_site_details']['fields']['published']['save_callback'])) {
            foreach ($GLOBALS['TL_DCA']['tl_site_details']['fields']['published']['save_callback'] as $callback) {
                $str_class = $callback[0];
                $str_function = $callback[1];

                if ($str_class && $str_function) {
                    $this->import($str_class);
                    $blnVisible = $this->$str_class->$str_function($blnVisible, $this);
                }
            }
        }

        // Update the database
        $this->Database->prepare("UPDATE tl_site_details SET tstamp=". time() .", published='" . ($blnVisible ? 1 : 0) . "'  WHERE id=?")
                       ->execute($intId);

       $result = $this->Database->prepare("SELECT pid, CONCAT_WS(';',pid,speech,published) AS detaillink FROM `tl_site_details` WHERE `id` = ?")
                   ->execute([$intId]);

       $link = $result->detaillink;
       $link_parts = explode(";",$link);
       $pid = $link_parts[0];
       $locatedLink = $intId.';'.$link_parts[1].';'.$link_parts[2];

       $getStoredIds = $this->Database->prepare('SELECT `details_link` FROM `tl_site` WHERE `id` = ?')
                           -> execute([$pid]);

       if($getStoredIds->details_link != '') {
           $allIds = json_decode($getStoredIds->details_link, true);

           foreach ($allIds as $key=>$val) {
              //$tmp = explode(";",$val);
              if ($val == $intId || $val == $link_parts[1] || strpos($val ,"XXX")!==false) {
                  unset($allIds[$key]);
              }
          }
           $actId = json_encode($allIds);
       }

       if(!in_array($locatedLink, $allIds)) {
           $allIds[] = $locatedLink;
           $allIdsAsString = json_encode($allIds);
           $setChildToParent = $this->Database->prepare('UPDATE `tl_site` SET `details_link` = ? WHERE `id` = ?')
                                   ->execute([$allIdsAsString, $pid]);
       }

        $this->createNewVersion('tl_site_details', $intId);

    }

}
