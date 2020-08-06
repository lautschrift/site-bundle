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
         $myID = \Contao\Input::get('sid');
         $db = \Contao\Database::getInstance();
         $resultDetails = $db->prepare('SELECT * FROM `tl_site_details` WHERE `id`= ?')
            ->execute([$myID])->fetchAllAssoc();

         $detailParent = $resultDetails->pid;

         $resultSite = $db->prepare('SELECT * FROM `tl_site` WHERE `id`= ?')
               ->execute([$detailParent])->fetchAllAssoc();
         $template->detailParent = $detailParent;
         $template->sitedetails = $resultDetails;
         $template->site = $resultSite;

         return $template->getResponse();
      }
}
