# WordPress Core Installer
This is a [custom Composer installer](http://getcomposer.org/doc/articles/custom-installers.md) for WordPress core. It is a proof of concept, but feel free to use it. The package is on [packagist](http://packagist.org) and the package name is `haydenk/wordpress-core-installer`.

[![Build Status](https://travis-ci.org/haydenk/wordpress-core-installer.svg?branch=master)](https://travis-ci.org/haydenk/wordpress-core-installer)

### Usage
To set up a custom WordPress build package to use this as a custom installer, add the following to your package's composer file:

```
"repositories": [
    {
        "type": "package",
        "package": {
            "name": "wordpress/wordpress",
            "type": "wordpress-core",
            "version": "4.6.1",
            "dist": {
                "type": "zip",
                "url": "https://github.com/WordPress/WordPress/archive/4.6.1.zip"
            }
        }
    }
],
"require": {
    "wordpress/wordpress": "4.6.1",
    "haydenk/wordpress-core-installer": "^0.1"
},
"extra" : {
    "wordpress-install-dir": {
        "wordpress/wordpress": "wp"
    }
}
```
