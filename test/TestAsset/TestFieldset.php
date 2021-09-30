<?php

namespace LaminasTest\Mvc\Plugin\FilePrg\TestAsset;

use Laminas\Form\Fieldset;
use Laminas\InputFilter\InputFilterProviderInterface;

class TestFieldset extends Fieldset implements InputFilterProviderInterface
{
    public function __construct($name = null, $options = [])
    {
        parent::__construct($name, $options);
        $this->add([
            'name' => 'text',
            'type' => 'text',
        ]);

        $this->add([
            'name' => 'file',
            'type' => 'file',
        ]);
    }

    public function getInputFilterSpecification()
    {
        return [
            'text' => [
                'required' => true,
            ],
            'file' => [
                'required' => true,
                'filters' => [
                    [
                        'name' => 'filerenameupload',
                        'options' => [
                            'target'    => __DIR__ . '/testfile.jpg',
                            'overwrite' => true,
                        ]
                    ]
                ],
            ],
        ];
    }
}
