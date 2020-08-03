<?php

//namespace Lautschrift\SiteBundle\Controller;
namespace App\Controller;

use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\CoreBundle\ServiceAnnotation\FrontendModule;
use Contao\ModuleModel;
use Contao\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @FrontendModule(category="miscellaneous")
 */
class SiteController extends AbstractFrontendModuleController
{
    protected function getResponse(Template $template, ModuleModel $model, Request $request): ?Response
       {
           if ($request->isMethod('post')) {
              /* if (null !== ($redirectPage = PageModel::findByPk($model->jumpTo))) {
                   throw new RedirectResponseException($redirectPage->getAbsoluteUrl());
               }*/
           }

           $template->unescoid = $model->unescoid;
           return $template->getResponse();

       }
}
