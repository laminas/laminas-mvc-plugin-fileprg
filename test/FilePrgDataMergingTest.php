<?php
/**
 * @link      http://github.com/zendframework/zend-mvc-plugin-fileprg for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZendTest\Mvc\Plugin\FilePrg;

use PHPUnit_Framework_TestCase as TestCase;
use Zend\Form\Form;
use Zend\Stdlib\Parameters;
use Zend\Validator\NotEmpty;

/**
 * @runTestsInSeparateProcesses
 */
class FilePrgDataMergingTest extends TestCase
{
    use CommonSetupTrait;

    public function disablePhpUploadCapabilities()
    {
        require_once __DIR__ . '/TestAsset/DisablePhpUploadChecks.php';
        require_once __DIR__ . '/TestAsset/DisablePhpMoveUploadedFileChecks.php';
    }

    public function testCorrectInputDataMerging()
    {
        $this->disablePhpUploadCapabilities();

        $form = new Form();
        $form->add([
            'name' => 'collection',
            'type' => 'collection',
            'options' => [
                'target_element' => new TestAsset\TestFieldset('target'),
                'count' => 2,
            ]
        ]);

        copy(__DIR__ . '/TestAsset/nullfile', __DIR__ . '/TestAsset/nullfile_copy');

        $request = $this->request;
        $request->setMethod('POST');
        $request->setPost(new Parameters([
            'collection' => [
                0 => [
                    'text' => 'testvalue1',
                ],
                1 => [
                    'text' => '',
                ]
            ]
        ]));
        $request->setFiles(new Parameters([
            'collection' => [
                0 => [
                    'file' => [
                        'name' => 'test.jpg',
                        'type' => 'image/jpeg',
                        'size' => 20480,
                        'tmp_name' => __DIR__ . '/TestAsset/nullfile_copy',
                        'error' => UPLOAD_ERR_OK
                    ],
                ],
            ]
        ]));

        $this->controller->dispatch($this->request, $this->response);

        $plugin = $this->plugin;
        $plugin($form, '/test/getPage', true);

        $this->assertFalse($form->isValid());
        $data = $form->getData();

        // @codingStandardsIgnoreStart
        $this->assertEquals([
            'collection' => [
                0 => [
                    'text' => 'testvalue1',
                    'file' => [
                        'name' => 'test.jpg',
                        'type' => 'image/jpeg',
                        'size' => 20480,
                        'tmp_name' => __DIR__ . DIRECTORY_SEPARATOR . 'TestAsset' . DIRECTORY_SEPARATOR . 'testfile.jpg',
                        'error' => 0
                    ],
                ],
                1 => [
                    'text' => null,
                    'file' => null,
                ]
            ]
        ], $data);
        // @codingStandardsIgnoreEnd

        $this->assertFileExists($data['collection'][0]['file']['tmp_name']);

        unlink($data['collection'][0]['file']['tmp_name']);

        $messages = $form->getMessages();
        $this->assertTrue(isset($messages['collection'][1]['text'][NotEmpty::IS_EMPTY]));

        $requiredFound = false;
        foreach ($messages['collection'][1]['file'] as $message) {
            if (strpos($message, 'Value is required') === 0) {
                $requiredFound = true;
                break;
            }
        }
        $this->assertTrue($requiredFound, '"Required" message was not found in validation failure messages');
    }
}
