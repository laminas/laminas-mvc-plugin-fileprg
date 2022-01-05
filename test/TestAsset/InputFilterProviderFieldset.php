<?php

declare(strict_types=1);

namespace LaminasTest\Mvc\Plugin\FilePrg\TestAsset;

use Laminas\Filter\StringToUpper;
use Laminas\Form\Fieldset;
use Laminas\InputFilter\InputFilterProviderInterface;

class InputFilterProviderFieldset extends Fieldset implements InputFilterProviderInterface
{
    /**
     * @param null|string $name
     * @param array|ArrayAccess $options
     */
    public function __construct($name = null, $options = [])
    {
        parent::__construct($name, $options);

        $this->add([
            'name' => 'test_field',
        ]);
    }

    /** @return array */
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
