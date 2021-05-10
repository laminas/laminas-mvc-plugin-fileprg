<?php

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
