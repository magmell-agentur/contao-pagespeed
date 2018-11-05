<?php

namespace Magmell\Contao\PageSpeed\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Magmell\Contao\PageSpeed\ContaoPageSpeedBundle;

class Plugin implements BundlePluginInterface
{
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create(ContaoPageSpeedBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class])
        ];
    }
}
