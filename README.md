# laminas-mvc-plugin-fileprg

[![Build Status](https://github.com/laminas/laminas-mvc-plugin-fileprg/workflows/continuous-integration.yml/badge.svg)](https://github.com/laminas/laminas-mvc-plugin-fileprg/actions/workflows/continuous-integration.yml")

Provides a [Post-Redirect-Get](https://en.wikipedia.org/wiki/Post/Redirect/Get)
controller plugin for [laminas-mvc](https://docs.laminas.dev/laminas-mvc/)
versions 3.0 and up, specifically for submissions that include file uploads.

If you want a generic PRG plugin without file upload support, see
[laminas-mvc-plugin-prg](https://docs.laminas.dev/laminas-mvc-plugin-prg).

## Installation

Run the following to install this library:

```bash
$ composer require laminas/laminas-mvc-plugin-fileprg
```

If you are using the [laminas-component-installer](https://docs.laminas.dev/laminas-component-installer/),
you're done!

If not, you will need to add the component as a module to your
application. Add the entry `'Laminas\Mvc\Plugin\FilePrg'` to
your list of modules in your application configuration (typically
one of `config/application.config.php` or `config/modules.config.php`).

## Documentation

Browse the documentation online at https://docs.laminas.dev/laminas-mvc-plugin-fileprg/

## Support

* [Issues](https://github.com/laminas/laminas-mvc-plugin-fileprg/issues/)
* [Chat](https://laminas.dev/chat/)
* [Forum](https://discourse.laminas.dev/)
