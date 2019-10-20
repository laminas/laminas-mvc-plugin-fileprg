# zend-mvc-plugin-fileprg

[![Build Status](https://secure.travis-ci.org/zendframework/zend-mvc-plugin-fileprg.svg?branch=master)](https://secure.travis-ci.org/zendframework/zend-mvc-plugin-fileprg)
[![Coverage Status](https://coveralls.io/repos/github/zendframework/zend-mvc-plugin-fileprg/badge.svg?branch=master)](https://coveralls.io/github/zendframework/zend-mvc-plugin-fileprg?branch=master)

Provides a [Post-Redirect-Get](https://en.wikipedia.org/wiki/Post/Redirect/Get)
controller plugin for [zend-mvc](https://docs.zendframework.com/zend-mvc/)
versions 3.0 and up, specifically for submissions that include file uploads.

If you want a generic PRG plugin without file upload support, see
[zend-mvc-plugin-prg](https://docs.zendframework.com/zend-mvc-plugin-prg).

## Installation

Run the following to install this library:

```bash
$ composer require zendframework/zend-mvc-plugin-fileprg
```

If you are using the [zend-component-installer](https://docs.zendframework.com/zend-component-installer/),
you're done!

If not, you will need to add the component as a module to your
application. Add the entry `'Zend\Mvc\Plugin\FilePrg'` to
your list of modules in your application configuration (typically
one of `config/application.config.php` or `config/modules.config.php`).

## Documentation

Browse the documentation online at https://docs.zendframework.com/zend-mvc-plugin-fileprg/

## Support

* [Issues](https://github.com/zendframework/zend-mvc-plugin-fileprg/issues/)
* [Chat](https://zendframework-slack.herokuapp.com/)
* [Forum](https://discourse.zendframework.com/)
