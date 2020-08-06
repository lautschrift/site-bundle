<?php

namespace Lautschrift\SiteBundle\EventListener;

use Contao\CoreBundle\ServiceAnnotation\Hook;
use Contao\FrontendTemplate;
use Contao\Module;

class GetSiteListener {
    /**
     * @Hook("getSite")
     */
    public function onParseArticles(FrontendTemplate $template, array $newsEntry, Module $module): void
    {
        // Do something …
        echo "GetSiteListener -> Test";
    }
}
