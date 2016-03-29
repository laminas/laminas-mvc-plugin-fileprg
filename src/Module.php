<?php
/**
 * @link      http://github.com/zendframework/zend-mvc-plugin-fileprg for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Zend\Mvc\Plugin\FilePrg;

use Zend\ServiceManager\Factory\InvokableFactory;

class Module
{
    /**
     * Provide application configuration.
     *
     * Adds aliases and factories for the FilePostRedirectGet plugin.
     *
     * @return array
     */
    public function getConfig()
    {
        return [
            'controller_plugins' => [
                'aliases' => [
                    'fileprg'             => FilePostRedirectGet::class,
                    'FilePostRedirectGet' => FilePostRedirectGet::class,
                    'filePostRedirectGet' => FilePostRedirectGet::class,
                    'filepostredirectget' => FilePostRedirectGet::class,
                    'Zend\Mvc\Controller\Plugin\FilePostRedirectGet' => FilePostRedirectGet::class,
                ],
                'factories' => [
                    FilePostRedirectGet::class => InvokableFactory::class,
                ],
            ],
        ];
    }
}
