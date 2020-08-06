<?php

namespace Lautschrift\SiteBundle\EventListener;

use Contao\CoreBundle\ServiceAnnotation\Hook;
use Contao\FrontendTemplate;
use Contao\Module;


class GetSiteListener implements ServiceAnnotationInterface
{
    /**
     * @Hook("getSite")
     */
    public function onReplaceInsertTags(
        string $insertTag,
        bool $useCache,
        string $cachedValue,
        array $flags,
        array $tags,
        array $cache,
        int $_rit,
        int $_cnt
    )
    {
        if ('mytag' === $insertTag) {
            return 'mytag replacement';
        }

        return false;
    }
}
