<?php

/**
 * @see       https://github.com/laminas/laminas-mvc-plugin-fileprg for the canonical source repository
 * @copyright https://github.com/laminas/laminas-mvc-plugin-fileprg/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-mvc-plugin-fileprg/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Mvc\Plugin\FilePrg\TestAsset;

use Laminas\Form\Fieldset;
use Laminas\InputFilter\InputFilterProviderInterface;

class LinksFieldset extends Fieldset implements InputFilterProviderInterface
{
    public function __construct()
    {
        parent::__construct('link');
        $this->add([
            'name' => 'foobar',
        ]);
    }

    public function getInputFilterSpecification()
    {
        return [
            'email' => [
                'required' => false,
            ],
        ];
    }
}
