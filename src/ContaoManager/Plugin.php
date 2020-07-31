<?php
declare(strict_types = 1);
namespace Lautschrift\SiteBundle\ContaoManager;

use Lautschrift\SiteBundle\SiteBundle;
use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;

class Plugin implements BundlePluginInterface
{
	public function getBundles(ParserInterface $parser)
	{
		return [
				BundleConfig::create(SiteBundle::class)
				->setLoadAfter(['Contao\CoreBundle\ContaoCoreBundle', 'Contao\ManagerBundle\ContaoManagerBundle'])
				->setReplace(['site'])
		]; }
}
