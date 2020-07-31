<?php
namespace Lautschrift\SiteBundle\Resources\contao\classes;

class ContentSite extends \ContentElement
{
	protected $strTemplate = 'ce_site';


	public function generate()
	    {
	        if (TL_MODE == 'BE') {
	            $template = new \BackendTemplate('be_wildcard');
	            $template->wildcard = '### ~ SITE ~'.utf8_strtoupper($GLOBALS['TL_LANG']['CTE']['testelement'][0]).' ###';

	            return $template->parse();
	        }

	        return parent::generate();
	    }


	protected function compile()
	{
		$rs = \Database::getInstance()
		->query('SELECT * FROM tl_site');

		$this->Template->site = $rs->fetchAllAssoc();
		//return;
	}

}
