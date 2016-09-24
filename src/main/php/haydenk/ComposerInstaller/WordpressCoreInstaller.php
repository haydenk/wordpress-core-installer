<?php
namespace haydenk\ComposerInstaller;

use Composer\Composer;
use Composer\Installer\LibraryInstaller;
use Composer\IO\IOInterface;
use Composer\Package\Loader\InvalidPackageException;
use Composer\Package\PackageInterface;

class WordpressCoreInstaller extends LibraryInstaller
{
    public function __construct(IOInterface $io, Composer $composer, $type = 'wordpress-core')
    {
        parent::__construct($io, $composer, $type);
    }

    public function getInstallPath(PackageInterface $package)
    {
        $packageName = $package->getPrettyName();
        $packageExtra = $package->getExtra();

        if(false === array_key_exists('wordpress-install-dir', $packageExtra)) {
            return parent::getInstallPath($package);
        }

        $installDirs = $packageExtra['wordpress-install-dir'];

        if(false === is_array($installDirs)) {
            throw new InvalidPackageException(
                ['Installation directory must be a key value array of packages and install directories.'],
                [], [$installDirs]);
        }

        if(false === array_key_exists($packageName, $installDirs)) {
            throw new InvalidPackageException(
                ['Installation directory must be a key value array of packages and install directories.'],
                [], [$installDirs]);
        }

        $packageInstallDir = $installDirs[$packageName];

        $installDirCount = 0;
        foreach($installDirs as $installDir) {
            if($installDir === $packageInstallDir) {
                $installDirCount++;
            }
        }

        if($installDirCount > 1) {
            throw new InvalidPackageException(
                ['Two packages cannot have the same install directory'], [], $installDirs);
        }

        return $installDirs[$packageName];
    }
}