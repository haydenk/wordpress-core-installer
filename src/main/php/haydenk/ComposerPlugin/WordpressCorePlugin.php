<?php
namespace haydenk\ComposerPlugin;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use haydenk\ComposerInstaller\WordpressCoreInstaller;

class WordpressCorePlugin implements PluginInterface
{
    /**
     * Apply plugin modifications to Composer
     *
     * @param Composer $composer
     * @param IOInterface $io
     */
    public function activate(Composer $composer, IOInterface $io)
    {
        $installer = new WordpressCoreInstaller($io, $composer);
        $composer->getInstallationManager()->addInstaller($installer);
    }

}