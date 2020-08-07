<?php

namespace Lautschrift\SiteBundle\Controller\FrontendModule;

use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\CoreBundle\ServiceAnnotation\FrontendModule;
use Contao\ModuleModel;
use Contao\Template;
use Contao\Database;
use Contao\FilesModel;
use Contao\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @FrontendModule("siteoverview_module",
 *   category="Fundstellen",
 *   template="mod_siteoverview",
 * )
 */
class SiteOverviewModuleController extends AbstractFrontendModuleController
{
    protected function getResponse(Template $template, ModuleModel $model, Request $request): ?Response
      {
         global $objPage;
         $myID = \Contao\Input::get('id');
         $db = \Contao\Database::getInstance();
         $image = FilesModel::findByUuid($uuid);

         $result = $db->prepare('SELECT * FROM `tl_site`')
            ->execute();

         $sites = $result->fetchAllAssoc();
         $template->sites = $sites;
         $template->myimage = $image;

         Controller::addImageToTemplate($template, [
            'singleSRC' => $image->path,
            'size' => $size,
        ], null, null, $image);

         return $template->getResponse();
      }
}
