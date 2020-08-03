<?php

//namespace Lautschrift\SiteBundle\Controller;
namespace App\Controller\FrontendModule;

use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\CoreBundle\ServiceAnnotation\FrontendModule;
use Contao\ModuleModel;
use Contao\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @FrontendModule("site_module",
 *   category="miscellaneous",
 *   template="mod_site",
 *   renderer="forward"
 * )
 */
class SiteModule extends AbstractFrontendModuleController
{
    protected function getResponse(Template $template, ModuleModel $model, Request $request): ?Response
  {
      return $template->getResponse();
  }
}
