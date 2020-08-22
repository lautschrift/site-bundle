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
         $resultDetails = $db->prepare('SELECT * FROM `tl_site_details` WHERE `id`= ? AND `published`=1')
            ->execute([$myID]);

         $detailParent = $resultDetails->pid;
         $detailSpeech = $resultDetails->speech;

         if (strtoupper($objPage->language) !== $resultDetails->speech) {
             $storedLinks = $db->prepare('SELECT `details_link` FROM `tl_site` WHERE `id`= ?')
                   ->execute([$detailParent])->fetchAllAssoc();
             $allLinks = json_decode($storedLinks->details_link, true);
             foreach ($allLinks as $key => $val) {
                 $tmp = explode(";",$val);
                 if($tmp[1] === strtoupper($objPage->language)) {
                     $resultSite = $db->prepare('SELECT * FROM `tl_site` WHERE `id`= ?')
                           ->execute([$tmp[0]->fetchAllAssoc());
                     $template->detailParent = $detailParent;
                     $sitedetails = $resultDetails->fetchAllAssoc();
                     $template->sitedetails = $sitedetails[0];
                     $template->site = $resultSite[0];
                 }
            }
         } else {
             $resultSite = $db->prepare('SELECT * FROM `tl_site` WHERE `id`= ?')
                   ->execute([$detailParent])->fetchAllAssoc();
             $template->detailParent = $detailParent;
             $sitedetails = $resultDetails->fetchAllAssoc();
             $template->sitedetails = $sitedetails[0];
             $template->site = $resultSite[0];
         }




         return $template->getResponse();
      }
}
