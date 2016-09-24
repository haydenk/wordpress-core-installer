<?php
namespace haydenk\ComposerInstaller;

use Composer\Composer;
use Composer\Config;
use Composer\Installer\LibraryInstaller;
use Composer\IO\NullIO;
use Composer\Package\Loader\InvalidPackageException;
use Composer\Package\Package;
use Composer\Util\Filesystem;

class WordpressCoreInstallerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var WordpressCoreInstaller
     */
    private $installer;

    /**
     * @var Filesystem
     */
    private $fs;

    /**
     * @var string
     */
    private $vendorDir;

    protected function setUp()
    {
        $this->fs = new Filesystem();
        $composer = new Composer();
        $io = new NullIO();
        $this->vendorDir = __DIR__.'/vendor';
        $config = new Config();
        $config->merge(['config' => ['vendor-dir' => $this->vendorDir]]);
        $composer->setConfig($config);
        $this->installer = new WordpressCoreInstaller($io, $composer);
        $this->fs->ensureDirectoryExists($this->vendorDir);
    }

    protected function tearDown()
    {
        $this->fs->removeDirectory($this->vendorDir);
    }

    public function testIsALibraryInstaller()
    {
        $this->assertInstanceOf(LibraryInstaller::class, $this->installer, 'This does not appear to be a Composer Library Installer.');
    }

    public function testItSupportsWordpressCore()
    {
        $this->assertTrue($this->installer->supports('wordpress-core'));
    }

    /**
     * @dataProvider installPathPackageDataProvider
     * @param $packageName
     * @param $expectedInstallPath
     * @param $hasExtra
     */
    public function testGetInstallPath($packageName, $expectedInstallPath, $hasExtra)
    {
        $pkg = new Package($packageName, '4.6.1', '4.6.1');
        if($hasExtra) {
            $pkg->setExtra([
                'wordpress-install-dir' => [
                    'wordpress/wordpress' => 'wp',
                    'haydenk/wordpress' => 'hk-wp',
                ],
            ]);
        } else {
            $expectedInstallPath = realpath($this->vendorDir) . '/' . $packageName;
        }

        $this->assertEquals($expectedInstallPath, $this->installer->getInstallPath($pkg));
    }

    public function installPathPackageDataProvider()
    {
        return [
            ['wordpress/wordpress', 'wp', true],
            ['haydenk/wordpress', 'hk-wp', true],
            ['wordpress/wordpress', null, false],
        ];
    }

    /**
     * @dataProvider packageDataProvider
     * @param $packageName
     */
    public function testDuplicateInstallPathThrowsException($packageName)
    {
        $this->expectException(InvalidPackageException::class);

        $pkg = new Package($packageName, '4.6.1', '4.6.1');
        $pkg->setExtra([
            'wordpress-install-dir' => [
                'wordpress/wordpress' => 'wp',
                'haydenk/wordpress' => 'wp',
            ],
        ]);

        $this->installer->getInstallPath($pkg);
    }

    public function packageDataProvider()
    {
        return [
            ['wordpress/wordpress'],
            ['haydenk/wordpress'],
        ];
    }

    public function testExceptionWhenPackageExtraIsNotArray()
    {
        $this->expectException(InvalidPackageException::class);

        $pkg = new Package('wordpress/wordpress', '4.6.1', '4.6.1');
        $pkg->setExtra([
            'wordpress-install-dir' => 'wordpress/wordpress',
        ]);

        $this->installer->getInstallPath($pkg);
    }

    public function testExceptionWhenPackageNotFound()
    {
        $this->expectException(InvalidPackageException::class);

        $pkg = new Package('wordpress/wordpress', '4.6.1', '4.6.1');
        $pkg->setExtra([
            'wordpress-install-dir' => ['wordpress/wordpress'],
        ]);

        $this->installer->getInstallPath($pkg);
    }

}