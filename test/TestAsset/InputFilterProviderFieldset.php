<?php
/**
 * @link      http://github.com/zendframework/zend-mvc-plugin-fileprg for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZendTest\Mvc\Plugin\FilePrg\TestAsset;

use Zend\Filter\StringToUpper;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

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
