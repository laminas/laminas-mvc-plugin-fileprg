<?php

/**
 * @see       https://github.com/laminas/laminas-mvc-plugin-fileprg for the canonical source repository
 * @copyright https://github.com/laminas/laminas-mvc-plugin-fileprg/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-mvc-plugin-fileprg/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Mvc\Plugin\FilePrg\TestAsset;

use Laminas\Filter\StringToUpper;
use Laminas\Form\Fieldset;
use Laminas\InputFilter\InputFilterProviderInterface;

class InputFilterProviderFieldset extends Fieldset implements InputFilterProviderInterface
{
    public function __construct($name = null, $options = [])
    {
        parent::__construct($name, $options);

        $this->add([
            'name' => 'test_field',
        ]);
    }

    public function getInputFilterSpecification()
    {
        return [
            'test_field' => [
                'filters' => [
                    new StringToUpper(),
                ],
            ],
        ];
    }
}
