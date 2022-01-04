<?php

namespace LaminasTest\Mvc\Plugin\FilePrg;

use Laminas\Form\Form;
use Laminas\Stdlib\Parameters;
use Laminas\Validator\NotEmpty;
use PHPUnit\Framework\TestCase;

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

        // phpcs:disable Generic.Files.LineLength.TooLong
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
        // phpcs:enable Generic.Files.LineLength.TooLong

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
