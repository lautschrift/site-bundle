<?php

namespace Lautschrift\SiteBundle\Controller\FrontendModule;
//namespace App\Controller\FrontendModule;

use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\CoreBundle\ServiceAnnotation\FrontendModule;
use Contao\ModuleModel;
use Contao\Template;
use Contao\Database;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @FrontendModule("site_module",
 *   category="Fundstellen",
 *   template="mod_site",
 * )
 */
class SiteModuleController extends AbstractFrontendModuleController
{
    protected function getResponse(Template $template, ModuleModel $model, Request $request): ?Response
  {
      global $objPage;

     $sql = Database::getInstance()->execute("SELECT * FROM tl_site")->fetchAllAssoc();
     $template->unescoid = $sql;

     return $template->getResponse();
  }
}