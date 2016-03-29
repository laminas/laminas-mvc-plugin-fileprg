<?php
/**
 * @link      http://github.com/zendframework/zend-mvc-plugin-fileprg for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZendTest\Mvc\Plugin\FilePrg\TestAsset;

use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

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
