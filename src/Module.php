<?php

declare(strict_types=1);

namespace Laminas\Mvc\Plugin\FilePrg;

use Laminas\ServiceManager\Factory\InvokableFactory;

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
                'aliases'   => [
                    'fileprg'                                           => FilePostRedirectGet::class,
                    'FilePostRedirectGet'                               => FilePostRedirectGet::class,
                    'filePostRedirectGet'                               => FilePostRedirectGet::class,
                    'filepostredirectget'                               => FilePostRedirectGet::class,
                    'Laminas\Mvc\Controller\Plugin\FilePostRedirectGet' => FilePostRedirectGet::class,

                    // Legacy Zend Framework aliases
                    // @codingStandardsIgnoreStart
                    'Zend\Mvc\Controller\Plugin\FilePostRedirectGet'    => 'Laminas\Mvc\Controller\Plugin\FilePostRedirectGet',
                    \Zend\Mvc\Plugin\FilePrg\FilePostRedirectGet::class => FilePostRedirectGet::class,
                    // @codingStandardsIgnoreEnd
                ],
                'factories' => [
                    FilePostRedirectGet::class => InvokableFactory::class,
                ],
            ],
        ];
    }
}
