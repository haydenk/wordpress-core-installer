<?php
namespace haydenk\ComposerPlugin;

use Composer\Composer;
use Composer\Config;
use Composer\Installer\InstallationManager;
use Composer\IO\IOInterface;
use Composer\IO\NullIO;
use Composer\Plugin\PluginInterface;
use haydenk\ComposerInstaller\WordpressCoreInstaller;

class WordpressCorePluginTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var WordpressCorePlugin
     */
    private $plugin;

    /**
     * @var Composer
     */
    private $composer;

    /**
     * @var IOInterface
     */
    private $io;

    protected function setUp()
    {
        $this->plugin = new WordpressCorePlugin();
        $this->composer = new Composer();
        $this->io = new NullIO();
        $vendorDir = __DIR__.'/vendor';
        $config = new Config();
        $config->merge(['config' => ['vendor-dir' => $vendorDir]]);
        $this->composer->setConfig($config);
        $installationManager = new InstallationManager();
        $this->composer->setInstallationManager($installationManager);
    }

    public function testItIsAPlugin()
    {
        $this->assertInstanceOf(PluginInterface::class,
            $this->plugin, 'This does not appear to be a Composer Plugin.');
    }

    public function testPluginIsInstalled()
    {
        $this->plugin->activate($this->composer, $this->io);
        $this->assertInstanceOf(WordpressCoreInstaller::class,
            $this->composer->getInstallationManager()->getInstaller('wordpress-core'));
    }
}
